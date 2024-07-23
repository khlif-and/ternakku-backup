<?php

namespace App\Http\Controllers\Api;

use App\Models\LivestockSex;
use Illuminate\Http\Request;
use App\Models\LivestockType;
use App\Models\LivestockBreed;
use App\Models\LivestockGroup;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\LivestockBreedResource;

class DataMasterController extends Controller
{
    public function getLivestockType()
    {
        $data = LivestockType::all();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getLivestockSex()
    {
        $data = LivestockSex::all();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getLivestockGroup()
    {
        $data = LivestockGroup::all();

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function getLivestockBreed(Request $request)
    {
        $livestockTypeId = $request->query('livestock_type_id');

        $query = LivestockBreed::with('livestockType');

        if ($livestockTypeId) {
            $query->where('livestock_type_id', $livestockTypeId);
        }

        $data = LivestockBreedResource::collection($query->get());

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }
}
