<?php

namespace App\Http\Controllers\Api;

use App\Models\Farm;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\FarmResource;

class FarmController extends Controller
{
    public function detail($id)
    {
        // Find the Farm by ID
        $farm = Farm::find($id);

        // If farm not found, return error response
        if (!$farm) {
            return ResponseHelper::error(null, 'Farm not found', 404);
        }

        // If farm found, return it using the FarmResource
        $data = new FarmResource($farm);

        return ResponseHelper::success($data, 'Farm detail retrieved successfully');
    }
}
