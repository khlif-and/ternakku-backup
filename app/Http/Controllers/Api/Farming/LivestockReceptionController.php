<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Models\LivestockReceptionD;
use App\Models\LivestockReceptionH;
use App\Http\Controllers\Controller;
use App\Http\Resources\Farming\LivestockReceptionResource;
use App\Http\Requests\Farming\LivestockReceptionStoreRequest;
use App\Http\Requests\Farming\LivestockReceptionUpdateRequest;

class LivestockReceptionController extends Controller
{
    /**
     * Display a listing of the livestock receptions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($farmId, Request $request): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        // Mengambil LivestockReception yang terkait dengan farm tertentu
        $receptions = LivestockReceptionD::whereHas('livestockReceptionH', function ($query) use ($farm, $request) {
            $query->where('farm_id', $farm->id);

            // Filter berdasarkan start_date atau end_date
            if ($request->filled('start_date')) {
                $query->where('transaction_date', '>=', $request->input('start_date'));
            }

            if ($request->filled('end_date')) {
                $query->where('transaction_date', '<=', $request->input('end_date'));
            }
        });

        // Filter berdasarkan livestock_type_id, livestock_group_id, livestock_breed_id, dan livestock_sex_id
        if ($request->filled('livestock_type_id')) {
            $receptions->where('livestock_type_id', $request->input('livestock_type_id'));
        }

        if ($request->filled('livestock_group_id')) {
            $receptions->where('livestock_group_id', $request->input('livestock_group_id'));
        }

        if ($request->filled('livestock_breed_id')) {
            $receptions->where('livestock_breed_id', $request->input('livestock_breed_id'));
        }

        if ($request->filled('livestock_sex_id')) {
            $receptions->where('livestock_sex_id', $request->input('livestock_sex_id'));
        }

        if ($request->filled('livestock_classification_id')) {
            $receptions->where('livestock_classification_id', $request->input('livestock_classification_id'));
        }

        if ($request->filled('pen_id')) {
            $receptions->where('pen_id', $request->input('pen_id'));
        }

        if ($request->filled('supplier')) {
            $receptions->whereHas('livestockReceptionH', function ($query) use ($request) {
                $query->where('supplier', 'like', '%' . $request->input('supplier') . '%');
            });
        }

        $data = LivestockReceptionResource::collection($receptions->get());

        $message = $receptions->count() > 0 ? 'Livestock Receptions retrieved successfully' : 'No Livestock Receptions found';
        return ResponseHelper::success($data, $message);
    }

    /**
     * Store a newly created livestock reception in storage.
     *
     * @param  \App\Http\Requests\LivestockReceptionStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LivestockReceptionStoreRequest $request, $farmId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $reception = null;

        try {

            DB::beginTransaction();
            // Simpan data header LivestockReceptionH
            $livestockReceptionH = LivestockReceptionH::create([
                'farm_id'           => $farm->id,
                'transaction_date'  => $validated['transaction_date'],
                'supplier'          => $validated['supplier'] ?? '',
                'notes'             => $validated['notes'],
            ]);

            $receptionData = [
                'livestock_reception_h_id' => $livestockReceptionH->id,
                'eartag_number'            => $validated['eartag_number'],
                'rfid_number'              => $validated['rfid_number'] ?? null,
                'livestock_type_id'        => $validated['livestock_type_id'],
                'livestock_group_id'       => $validated['livestock_group_id'],
                'livestock_breed_id'       => $validated['livestock_breed_id'],
                'livestock_sex_id'         => $validated['livestock_sex_id'],
                'livestock_classification_id' => $validated['livestock_classification_id'],
                'pen_id'                   => $validated['pen_id'],
                'age_years'                => $validated['age_years'],
                'age_months'               => $validated['age_months'],
                'weight'                   => $validated['weight'],
                'price_per_kg'             => $validated['price_per_kg'],
                'price_per_head'            => $validated['price_per_head'],
                'notes'                    => $validated['notes'] ?? null,
                'characteristics'          => $validated['characteristics'] ?? null,
            ];

            // 3. Upload foto jika ada
            if (isset($validated['photo']) && request()->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = 'receptions/';
                $receptionData['photo'] = uploadNeoObject($file, $fileName, $filePath);
            }

            // 4. Simpan reception detail
            $reception = LivestockReceptionD::create($receptionData);

            // 5. Simpan ke tabel Livestock
            $livestock = Livestock::create([
                'farm_id' => $livestockReceptionH->farm_id,
                'livestock_reception_d_id' => $reception->id,
                'livestock_status_id' => LivestockStatusEnum::HIDUP->value,
                'eartag_number' => $reception->eartag_number,
                'rfid_number' => $reception->rfid_number,
                'livestock_type_id' => $reception->livestock_type_id,
                'livestock_group_id' => $reception->livestock_group_id,
                'livestock_breed_id' => $reception->livestock_breed_id,
                'livestock_sex_id' => $reception->livestock_sex_id,
                'livestock_classification_id' => $reception->livestock_classification_id,
                'pen_id' => $reception->pen_id,
                'start_age_years' => $reception->age_years,
                'start_age_months' => $reception->age_months,
                'last_weight' => $reception->weight,
                'photo' => $reception->photo,
                'characteristics' => $reception->characteristics,
            ]);

             // 4. Simpan Phenotype (jika ada input)
            $phenotypeData = collect($validated)->only([
                'height', 'body_length', 'hip_height', 'hip_width',
                'chest_width', 'head_length', 'head_width', 'ear_length', 'body_weight'
            ])->filter(); // filter() agar hanya menyimpan data yang tidak null

            if ($phenotypeData->isNotEmpty()) {
                $livestock->livestockPhenotype()->create($phenotypeData->toArray());
            }

            DB::commit();

            return ResponseHelper::success(new LivestockReceptionResource($reception), 'Livestock Reception created successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Handle exceptions and return an error response
            return ResponseHelper::error( $e->getMessage(), 500);
        }

    }


    /**
     * Display the specified livestock reception.
     *
     * @param  int  $receptionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($farmId, $receptionId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        // Mencari LivestockReceptionD yang terkait dengan farm tertentu
        $reception = LivestockReceptionD::whereHas('livestockReceptionH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id);
        })->findOrFail($receptionId);

        return ResponseHelper::success(new LivestockReceptionResource($reception), 'Livestock Reception retrieved successfully');
    }

    /**
     * Update the specified livestock reception in storage.
     *
     * @param  \App\Http\Requests\LivestockReceptionUpdateRequest  $request
     * @param  int  $receptionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(LivestockReceptionUpdateRequest $request, $farmId, $receptionId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $reception = LivestockReceptionD::whereHas('livestockReceptionH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id);
        })->findOrFail($receptionId);

        DB::transaction(function () use ($validated, $reception, $farm) {
            // Update LivestockReceptionH (header)
            $reception->livestockReceptionH->update([
                'transaction_date' => $validated['transaction_date'],
                'supplier'         => $validated['supplier'] ?? '',
                'notes'            => $validated['notes'] ?? null,
            ]);

            // Persiapkan data untuk update reception detail
            $receptionData = [
                'livestock_reception_h_id'   => $reception->livestockReceptionH->id,
                'eartag_number'              => $validated['eartag_number'],
                'rfid_number'                => $validated['rfid_number'] ?? null,
                'livestock_type_id'          => $validated['livestock_type_id'],
                'livestock_group_id'         => $validated['livestock_group_id'],
                'livestock_breed_id'         => $validated['livestock_breed_id'],
                'livestock_sex_id'           => $validated['livestock_sex_id'],
                'livestock_classification_id'=> $validated['livestock_classification_id'],
                'pen_id'                     => $validated['pen_id'],
                'age_years'                  => $validated['age_years'],
                'age_months'                 => $validated['age_months'],
                'weight'                     => $validated['weight'],
                'price_per_kg'               => $validated['price_per_kg'],
                'price_per_head'             => $validated['price_per_head'],
                'notes'                      => $validated['notes'] ?? null,
                'characteristics'            => $validated['characteristics'] ?? null,
            ];

            // Handle photo upload (replace old)
            if (isset($validated['photo']) && request()->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = 'receptions/';

                if ($reception->photo) {
                    deleteNeoObject($reception->photo);
                }

                $receptionData['photo'] = uploadNeoObject($file, $fileName, $filePath);
            }

            // Update reception detail
            $reception->update($receptionData);

            // Update Livestock (sinkronisasi)
            $livestock = $reception->livestock;

            $livestock->update([
                'livestock_status_id'         => LivestockStatusEnum::HIDUP->value,
                'eartag_number'               => $reception->eartag_number,
                'rfid_number'                 => $reception->rfid_number,
                'livestock_type_id'           => $reception->livestock_type_id,
                'livestock_group_id'          => $reception->livestock_group_id,
                'livestock_breed_id'          => $reception->livestock_breed_id,
                'livestock_sex_id'            => $reception->livestock_sex_id,
                'livestock_classification_id' => $reception->livestock_classification_id,
                'pen_id'                      => $reception->pen_id,
                'start_age_years'             => $reception->age_years,
                'start_age_months'            => $reception->age_months,
                'last_weight'                 => $reception->weight,
                'photo'                       => $reception->photo,
                'characteristics'             => $reception->characteristics,
            ]);

            // Update atau buat phenotype
            $phenotypeData = collect($validated)->only([
                'height', 'body_length', 'hip_height', 'hip_width',
                'chest_width', 'head_length', 'head_width', 'ear_length', 'body_weight'
            ])->filter();

            if ($phenotypeData->isNotEmpty()) {
                $livestock->livestockPhenotype()->updateOrCreate([], $phenotypeData->toArray());
            }
        });

        return ResponseHelper::success(new LivestockReceptionResource($reception->fresh()), 'Livestock Reception updated successfully');
    }


    /**
     * Remove the specified livestock reception from storage.
     *
     * @param  int  $receptionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($farmId, $receptionId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        return DB::transaction(function () use ($receptionId, $farm) {
            // Mencari LivestockReceptionD yang terkait dengan farm tertentu
            $reception = LivestockReceptionD::whereHas('livestockReceptionH', function ($query) use ($farm) {
                $query->where('farm_id', $farm->id);
            })->findOrFail($receptionId);

            // Hapus foto jika ada
            if ($reception->photo) {
                deleteNeoObject($reception->photo);
            }

            // Ambil header terkait
            $livestockReceptionH = $reception->livestockReceptionH;

            // Hapus LivestockReceptionD
            $reception->delete();

            // Cek apakah LivestockReceptionH masih memiliki LivestockReceptionD terkait
            if ($livestockReceptionH->livestockReceptionD()->count() === 0) {
                // Hapus LivestockReceptionH jika tidak ada LivestockReceptionD terkait
                $livestockReceptionH->delete();
            }

            return ResponseHelper::success(null, 'Livestock Reception deleted successfully', 200);
        });
    }
}
