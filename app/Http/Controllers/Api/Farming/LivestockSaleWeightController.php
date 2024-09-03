<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LivestockSaleWeightD;
use App\Models\LivestockSaleWeightH;
use App\Http\Resources\Farming\LivestockSaleWeightResource;
use App\Http\Requests\Farming\LivestockSaleWeightStoreRequest;
use App\Http\Requests\Farming\LivestockSaleWeightUpdateRequest;

class LivestockSaleWeightController extends Controller
{
    public function index($farmId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        // Mengambil LivestockSaleWeightD yang terkait dengan farm tertentu
        $saleWeights = LivestockSaleWeightD::whereHas('livestockSaleWeightH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id);
        })->get();

        $data = LivestockSaleWeightResource::collection($saleWeights);

        $message = $saleWeights->count() > 0 ? 'Livestock Sale Weights retrieved successfully' : 'No Livestock Sale Weights found';
        return ResponseHelper::success($data, $message);
    }

    public function store(LivestockSaleWeightStoreRequest $request, $farmId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $saleWeightD = null;

        DB::beginTransaction();

        try {
            // Find the livestock record
            $livestock = Livestock::find($validated['livestock_id']);

            // Check if the livestock exists
            if (!$livestock) {
                return ResponseHelper::error('Livestock not found.', 404);
            }

            // Check if the livestock is already deceased
            if ($livestock->livestock_status_id !== LivestockStatusEnum::HIDUP->value) {
                return ResponseHelper::error('This livestock not found', 404);
            }

            // Simpan data header LivestockSaleWeightH
            $livestockSaleWeightH = LivestockSaleWeightH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'customer'         => $validated['customer'],
                'notes'            => $validated['notes'],
            ]);

            // Siapkan data untuk LivestockSaleWeightD
            $saleWeightDData = $validated;
            $saleWeightDData['livestock_sale_weight_h_id'] = $livestockSaleWeightH->id;

            // Hapus supplier dari $receptionData karena tidak ada di tabel LivestockReception
            unset($saleWeightDData['customer']);
            unset($saleWeightDData['transaction_date']);

            // Handle file upload if present
            if (isset($validated['photo']) && request()->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = 'sale_weights/';
                $saleWeightDData['photo'] = uploadNeoObject($file, $fileName, $filePath);
            }

            // Simpan LivestockSaleWeightD dengan data yang telah di-assign
            $saleWeightD = LivestockSaleWeightD::create($saleWeightDData);

            $livestock->livestock_status_id = LivestockStatusEnum::TERJUAL->value;
            $livestock->save();

            DB::commit();

            return ResponseHelper::success(new LivestockSaleWeightResource($saleWeightD), 'Livestock Sale Weight created successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error(null, 'An error occurred while recording the livestock saleweight.', 500);
        }

    }

    public function show($farmId, $saleWeightId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        // Mencari LivestockSaleWeightD yang terkait dengan farm tertentu
        $saleWeight = LivestockSaleWeightD::whereHas('livestockSaleWeightH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id);
        })->findOrFail($saleWeightId);

        return ResponseHelper::success(new LivestockSaleWeightResource($saleWeight), 'Livestock Sale Weight retrieved successfully');
    }

    public function update(LivestockSaleWeightUpdateRequest $request, $farmId, $livestockSaleWeightId): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Dapatkan farm dari atribut request
            $farm = request()->attributes->get('farm');

            // Ambil data yang sudah tervalidasi
            $validated = $request->validated();

            // Cari record LivestockSaleWeightD
            $livestockSaleWeightD = LivestockSaleWeightD::whereHas('livestockSaleWeightH', function ($query) use ($farm) {
                $query->where('farm_id', $farm->id);
            })->findOrFail($livestockSaleWeightId);

            $livestockSaleWeightH = $livestockSaleWeightD->livestockSaleWeightH;

            $livestockSaleWeightH->update([
                'transaction_date' => $validated['transaction_date'],
                'customer'      => $validated['customer'],
                'notes'            => $validated['notes'],
            ]);

            //Ambil ID ternak lama
            $oldLivestockId = $livestockSaleWeightD->livestock_id;

            // Update data LivestockSaleWeightD
            $livestockSaleWeightD->update([
                'livestock_id' => $validated['livestock_id'],
                'weight' => $validated['weight'],
                'price_per_kg' => $validated['price_per_kg'],
                'price_per_head' => $validated['price_per_head'],
                'notes' => $validated['notes'] ?? $livestockSaleWeightD->notes,
            ]);

            // Handle file upload if present
            if (isset($validated['photo']) && request()->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = 'livestock_sales/';

                // Delete the old photo if it exists
                if ($livestockSaleWeightD->photo) {
                    deleteNeoObject($livestockSaleWeightD->photo);
                }

                // Upload new photo
                $livestockSaleWeightD->photo = uploadNeoObject($file, $fileName, $filePath);
                $livestockSaleWeightD->save();
            }

            // Perbarui status ternak lama jika livestock_id berubah
            if ($oldLivestockId && $oldLivestockId != $validated['livestock_id']) {
                $oldLivestock = Livestock::find($oldLivestockId);
                if ($oldLivestock && $oldLivestock->livestock_status_id === LivestockStatusEnum::TERJUAL->value) {
                    // Ubah status menjadi 'HIDUP' jika sebelumnya ditandai sebagai 'TERJUAL'
                    $oldLivestock->livestock_status_id = LivestockStatusEnum::HIDUP->value;
                    $oldLivestock->save();
                }
            }

            // Perbarui status ternak baru menjadi 'TERJUAL' jika diperlukan
            $newLivestock = Livestock::find($validated['livestock_id']);
            if ($newLivestock && $newLivestock->livestock_status_id !== LivestockStatusEnum::TERJUAL->value) {
                $newLivestock->livestock_status_id = LivestockStatusEnum::TERJUAL->value;
                $newLivestock->save();
            }

            DB::commit();

            // Kembalikan resource yang telah diperbarui menggunakan LivestockSaleWeightResource
            return ResponseHelper::success(new LivestockSaleWeightResource($livestockSaleWeightD), 'Livestock sale weight updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Tangani exception dan kembalikan respons error
            return ResponseHelper::error(null, 'An error occurred while updating the livestock sale weight.', 500);
        }
    }


    public function destroy($farmId, $saleWeightId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        return DB::transaction(function () use ($saleWeightId, $farm) {
            // Mencari LivestockSaleWeightD yang terkait dengan farm tertentu
            $saleWeightDetail = LivestockSaleWeightD::whereHas('livestockSaleWeightH', function ($query) use ($farm) {
                $query->where('farm_id', $farm->id);
            })->findOrFail($saleWeightId);

            // Ubah status ternak menjadi 'HIDUP' sebelum menghapusnya
            $livestock = Livestock::find($saleWeightDetail->livestock_id);
            if ($livestock && $livestock->livestock_status_id === LivestockStatusEnum::TERJUAL->value) {
                $livestock->livestock_status_id = LivestockStatusEnum::HIDUP->value;
                $livestock->save();
            }

            // Hapus foto jika ada
            if ($saleWeightDetail->photo) {
                deleteNeoObject($saleWeightDetail->photo);
            }

            // Ambil header terkait
            $livestockSaleWeightH = $saleWeightDetail->livestockSaleWeightH;

            // Hapus LivestockSaleWeightD
            $saleWeightDetail->delete();

            // Cek apakah LivestockSaleWeightH masih memiliki LivestockSaleWeightD terkait
            if ($livestockSaleWeightH->livestockSaleWeightD()->count() === 0) {
                // Hapus LivestockSaleWeightH jika tidak ada LivestockSaleWeightD terkait
                $livestockSaleWeightH->delete();
            }

            return ResponseHelper::success(null, 'Livestock sale weight deleted successfully', 200);
        });
    }
}
