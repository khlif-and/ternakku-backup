<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Models\LivestockBreed;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Qurban\LivestockBreedListResource;

class BreedController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter query
        $livestockTypeId = $request->query('livestock_type_id');
        $searchTerm = $request->query('search');

        $query = LivestockBreed::with([
            'livestockType',
        ]);

        if ($livestockTypeId) {
            $query->where('livestock_type_id', $livestockTypeId);
        }

        if ($searchTerm) {
            $q->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        // Eksekusi query dan ambil hasilnya
        $data = LivestockBreedListResource::collection($query->get());

        // Tentukan pesan respons
        $message = $query->count() > 0 ? 'Livestock retrieved successfully' : 'Data empty';

        // Kembalikan respons dengan data dan pesan
        return ResponseHelper::success($data, $message);
    }
}
