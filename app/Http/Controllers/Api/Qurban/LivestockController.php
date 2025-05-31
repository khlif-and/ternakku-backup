<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Qurban\LivestockResource;
use App\Http\Resources\Qurban\LivestockDetailResource;
use App\Http\Resources\LivestockResource as GlobalLivestockResource;

class LivestockController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter query
        $livestockTypeId = $request->query('livestock_type_id');
        $searchTerm = $request->query('search');

        // Buat query untuk Livestock dengan scope qurban dan eager load relasi yang diperlukan
        $query = Livestock::qurban()->with([
            'livestockReceptionD.livestockReceptionH.farm',
            'livestockReceptionD.livestockType',
            'livestockReceptionD.livestockBreed',
            'qurbanLivestock'
        ]);

        // Tambahkan kondisi filter berdasarkan livestock_type_id jika ada
        if ($livestockTypeId) {
            $query->whereHas('livestockReceptionD', function ($q) use ($livestockTypeId) {
                $q->where('livestock_type_id', $livestockTypeId);
            });
        }

        // Tambahkan kondisi pencarian berdasarkan farm_name dan breed_name jika ada
        if ($searchTerm) {
            $query->whereHas('livestockReceptionD.livestockReceptionH.farm', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%');
            })->orWhereHas('livestockReceptionD.livestockBreed', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Eksekusi query dan ambil hasilnya
        $data = LivestockResource::collection($query->get());

        // Tentukan pesan respons
        $message = $query->count() > 0 ? 'Livestock retrieved successfully' : 'Data empty';

        // Kembalikan respons dengan data dan pesan
        return ResponseHelper::success($data, $message);
    }

    public function detail($id)
    {
        // Find the Livestock by ID
        $livestock = Livestock::qurban()->find($id);

        // If Livestock not found, return error response
        if (!$livestock) {
            return ResponseHelper::error('Livestock not found', 404);
        }

        // If Livestock found, return it using the PartnerResource
        $data = new LivestockDetailResource($livestock);

        return ResponseHelper::success($data, 'Livestock detail retrieved successfully');
    }

    public function updateVaccineSkkh(Request $request, $farm_id, $id)
    {
        $validated = $request->validate([
            'vaccine' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'skkh' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $farm = request()->attributes->get('farm');

        $livestock = Livestock::whereHas('livestockReceptionD.livestockReceptionH', function ($q) use ($farm) {
            $q->where('farm_id', $farm->id);
        })->find($id);

        if (!$livestock) {
            return ResponseHelper::error('Livestock not found', 404);
        }

        // Handle vaccine file
        if (isset($validated['vaccine']) && request()->hasFile('vaccine')) {
            $file = $validated['vaccine'];
            $fileName = time() . '-vaccine-' . $file->getClientOriginalName();
            $filePath = 'livestock/vaccine/';

            // Delete old file if exists
            if ($livestock->vaccine) {
                deleteNeoObject($livestock->vaccine);
            }

            // Upload new file
            $livestock->vaccine = uploadNeoObject($file, $fileName, $filePath);
        }

        // Handle SKKH file
        if (isset($validated['skkh']) && request()->hasFile('skkh')) {
            $file = $validated['skkh'];
            $fileName = time() . '-skkh-' . $file->getClientOriginalName();
            $filePath = 'livestock/skkh/';

            // Delete old file if exists
            if ($livestock->skkh) {
                deleteNeoObject($livestock->skkh);
            }

            // Upload new file
            $livestock->skkh = uploadNeoObject($file, $fileName, $filePath);
        }

        $livestock->save();

        return ResponseHelper::success(new GlobalLivestockResource($livestock), 'Vaccine and SKKH updated successfully');
    }
}
