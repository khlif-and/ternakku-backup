<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Enums\LivestockStatusEnum;
use App\Models\LivestockReweightD;
use App\Models\LivestockReweightH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\Farming\LivestockReweightResource;
use App\Http\Requests\Farming\LivestockReweightStoreRequest;
use App\Http\Requests\Farming\LivestockReweightUpdateRequest;

class LivestockReweightController extends Controller
{
    public function index($farmId, Request $request): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        // Mengambil LivestockReweightD yang terkait dengan farm tertentu
        $reweights = LivestockReweightD::whereHas('livestockReweightH', function ($query) use ($farm, $request) {
            $query->where('farm_id', $farm->id);

            // Filter berdasarkan start_date atau end_date dari transaction_number
            if ($request->filled('start_date')) {
                $query->where('transaction_date', '>=', $request->input('start_date'));
            }

            if ($request->filled('end_date')) {
                $query->where('transaction_date', '<=', $request->input('end_date'));
            }
        });

        // Filter berdasarkan relasi Livestock (misalnya livestock_type_id)
        if ($request->filled('livestock_type_id')) {
            $reweights->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_type_id', $request->input('livestock_type_id'));
            });
        }

        if ($request->filled('livestock_group_id')) {
            $reweights->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_group_id', $request->input('livestock_group_id'));
            });
        }

        if ($request->filled('livestock_breed_id')) {
            $reweights->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_breed_id', $request->input('livestock_breed_id'));
            });
        }

        if ($request->filled('livestock_sex_id')) {
            $reweights->whereHas('livestock', function ($query) use ($request) {
                $query->where('livestock_sex_id', $request->input('livestock_sex_id'));
            });
        }

        if ($request->filled('pen_id')) {
            $reweights->whereHas('livestock', function ($query) use ($request) {
                $query->where('pen_id', $request->input('pen_id'));
            });
        }

        if ($request->filled('livestock_id')) {
            $reweights->where('livestock_id', $request->input('livestock_id'));
        }

        $data = LivestockReweightResource::collection($reweights->get());

        $message = $reweights->count() > 0 ? 'Data retrieved successfully' : 'No Data found';
        return ResponseHelper::success($data, $message);
    }

    public function store(LivestockReweightStoreRequest $request, $farmId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

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

            // Simpan data header LivestockReweightH
            $livestockReweightH = LivestockReweightH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'],
            ]);

            $photo = null;

            // Handle file upload if present
            if (isset($validated['photo']) && request()->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = 'reweights/';
                $photo = uploadNeoObject($file, $fileName, $filePath);
            }

            $livestockReweightD = LivestockReweightD::create([
                'livestock_reweight_h_id' => $livestockReweightH->id,
                'livestock_id' => $validated['livestock_id'],
                'weight' => $validated['weight'],
                'photo' => $photo,
            ]);


            DB::commit();

            return ResponseHelper::success(new LivestockReweightResource($livestockReweightD), 'Data created successfully', 200);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( 'An error occurred while recording the data.', 500);
        }

    }

    public function show($farmId, $reweightId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        // Mencari LivestockReweightD yang terkait dengan farm tertentu
        $reweight = LivestockReweightD::whereHas('livestockReweightH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id);
        })->findOrFail($reweightId);

        return ResponseHelper::success(new LivestockReweightResource($reweight), 'Data retrieved successfully');
    }

    public function update(LivestockReweightUpdateRequest $request, $farmId, $livestockReweightId): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Dapatkan farm dari atribut request
            $farm = request()->attributes->get('farm');

            // Ambil data yang sudah tervalidasi
            $validated = $request->validated();

            // Cari record LivestockReweightD
            $livestockReweightD = LivestockReweightD::whereHas('livestockReweightH', function ($query) use ($farm) {
                $query->where('farm_id', $farm->id);
            })->findOrFail($livestockReweightId);

            $livestockReweightH = $livestockReweightD->livestockReweightH;

            $livestockReweightH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'],
            ]);

            // Update data LivestockReweightD
            $livestockReweightD->update([
                'livestock_id' => $validated['livestock_id'],
                'weight' => $validated['weight'],
            ]);

            // Handle file upload if present
            if (isset($validated['photo']) && request()->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = 'reweights/';

                // Delete the old photo if it exists
                if ($livestockReweightD->photo) {
                    deleteNeoObject($livestockReweightD->photo);
                }

                // Upload new photo
                $livestockReweightD->photo = uploadNeoObject($file, $fileName, $filePath);
                $livestockReweightD->save();
            }

            DB::commit();

            // Kembalikan resource yang telah diperbarui menggunakan LivestockReweightResource
            return ResponseHelper::success(new LivestockReweightResource($livestockReweightD), 'Data updated successfully.');

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            DB::rollBack();

            // Tangani exception dan kembalikan respons error
            return ResponseHelper::error( 'An error occurred while updating the Data.', 500);
        }
    }


    public function destroy($farmId, $reweightId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        return DB::transaction(function () use ($reweightId, $farm) {
            // Mencari LivestockReweightD yang terkait dengan farm tertentu
            $reweightDetail = LivestockReweightD::whereHas('livestockReweightH', function ($query) use ($farm) {
                $query->where('farm_id', $farm->id);
            })->findOrFail($reweightId);

            // Hapus foto jika ada
            if ($reweightDetail->photo) {
                deleteNeoObject($reweightDetail->photo);
            }

            // Ambil header terkait
            $livestockReweightH = $reweightDetail->livestockReweightH;

            // Hapus LivestockReweightD
            $reweightDetail->delete();

            // Cek apakah LivestockReweightH masih memiliki LivestockReweightD terkait
            if ($livestockReweightH->livestockReweightD()->count() === 0) {
                // Hapus LivestockReweightH jika tidak ada LivestockReweightD terkait
                $livestockReweightH->delete();
            }

            return ResponseHelper::success(null, 'Data deleted successfully', 200);
        });
    }
}
