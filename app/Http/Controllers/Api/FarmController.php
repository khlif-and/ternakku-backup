<?php

namespace App\Http\Controllers\Api;

use App\Models\Farm;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\FarmListResource;
use App\Http\Resources\FarmDetailResource;

class FarmController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $farms = Farm::where('owner_id' , $user->id)->get();

        $data = FarmListResource::collection($farms);

        // Tentukan pesan respons
        $message = $farms->count() > 0 ? 'Farms retrieved successfully' : 'Data empty';

        // Kembalikan respons dengan data dan pesan
        return ResponseHelper::success($data, $message);

    }

    public function detail($id)
    {
        // Find the Farm by ID
        $farm = Farm::find($id);

        // If farm not found, return error response
        if (!$farm) {
            return ResponseHelper::error('Farm not found', 404);
        }

        // If farm found, return it using the FarmResource
        $data = new FarmDetailResource($farm);

        return ResponseHelper::success($data, 'Farm detail retrieved successfully');
    }
}
