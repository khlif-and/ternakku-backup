<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Farm;
use App\Models\Livestock;
use App\Models\LivestockType;
use App\Enums\LivestockSexEnum;
use App\Helpers\ResponseHelper;
use App\Enums\LivestockTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\PenListResource;
use App\Http\Resources\LivestockListResource;

class DashboardController extends Controller
{
    public function getPen($farmId)
    {
        $user = auth()->user();

        // Find the Farm by ID
        $farm = Farm::where('owner_id' , $user->id)->find($farmId);

        // If farm not found, return error response
        if (!$farm) {
            return ResponseHelper::error('Farm not found', 404);
        }

        // If farm found, return it using the PartnerResource
        $data = PenListResource::collection($farm->pens);

        return ResponseHelper::success($data, 'Pens retrieved successfully');
    }

    public function livestockPopulationSummary($farmId)
    {
        $user = auth()->user();

        // Find the Farm by ID
        $farm = Farm::where('owner_id', $user->id)->find($farmId);

        // If farm not found, return error response
        if (!$farm) {
            return ResponseHelper::error('Farm not found', 404);
        }

        $livestockTypes = LivestockType::all();
        $summary = [];

        foreach ($livestockTypes as $type) {
            $typeId = $type->id;

            $summary[$type->name] = $farm->getLivestockSummary($typeId);
        }

        return ResponseHelper::success($summary, 'Population Summary retrieved successfully');
    }

    public function getLivestock($farmId)
    {
        $user = auth()->user();

        // Find the Farm by ID
        $farm = Farm::where('owner_id', $user->id)->find($farmId);

        // If farm not found, return error response
        if (!$farm) {
            return ResponseHelper::error('Farm not found', 404);
        }

        $data = LivestockListResource::collection($farm->livestocks()->get());

        return ResponseHelper::success($data, 'Livestocks retrieved successfully');
    }

}
