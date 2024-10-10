<?php

namespace App\Http\Controllers\Api\Farming;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
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
    public function index($farmId): JsonResponse
    {
        $farm = request()->attributes->get('farm');

        // Mengambil LivestockReception yang terkait dengan farm tertentu
        $receptions = LivestockReceptionD::whereHas('livestockReceptionH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id);
        })->get();

        $data = LivestockReceptionResource::collection($receptions);

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

        DB::transaction(function () use ($validated, $farm, &$reception) {
            // Simpan data header LivestockReceptionH
            $livestockReceptionH = LivestockReceptionH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'supplier'      => $validated['supplier'] ?? '',
                'notes'            => $validated['notes'],
            ]);

            // Siapkan data untuk LivestockReception
            $receptionData = $validated;
            $receptionData['livestock_reception_h_id'] = $livestockReceptionH->id;

            // Hapus supplier dari $receptionData karena tidak ada di tabel LivestockReception
            unset($receptionData['supplier']);
            unset($receptionData['transaction_date']);

            // Handle file upload if present
            if (isset($validated['photo']) && request()->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = 'receptions/';
                $receptionData['photo'] = uploadNeoObject($file, $fileName, $filePath);
            }

            // Simpan LivestockReception dengan data yang telah di-assign
            $reception = LivestockReceptionD::create($receptionData);
        });

        return ResponseHelper::success(new LivestockReceptionResource($reception), 'Livestock Reception created successfully', 200);
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
    public function update(LivestockReceptionUpdateRequest $request, $farmId , $receptionId): JsonResponse
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');
        $reception = LivestockReceptionD::whereHas('livestockReceptionH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id);
        })->findOrFail($receptionId);

        DB::transaction(function () use ($validated, $reception, $farm) {
            // Update data header LivestockReceptionH
            $livestockReceptionH = $reception->livestockReceptionH;

            $livestockReceptionH->update([
                'transaction_date' => $validated['transaction_date'],
                'supplier'      => $validated['supplier'] ?? '',
                'notes'            => $validated['notes'],
            ]);

            // Siapkan data untuk update LivestockReceptionD
            $receptionData = $validated;
            $receptionData['livestock_reception_h_id'] = $livestockReceptionH->id;

            // Hapus supplier dan transaction_date dari $receptionData karena tidak ada di tabel LivestockReceptionD
            unset($receptionData['supplier']);
            unset($receptionData['transaction_date']);

            // Handle file upload if present
            if (isset($validated['photo']) && request()->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = 'receptions/';

                // Delete the old photo if it exists
                if ($reception->photo) {
                    deleteNeoObject($reception->photo);
                }

                // Upload new photo
                $receptionData['photo'] = uploadNeoObject($file, $fileName, $filePath);
            }

            // Update LivestockReceptionD
            $reception->update($receptionData);
        });

        return ResponseHelper::success(new LivestockReceptionResource($reception), 'Livestock Reception updated successfully');
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
