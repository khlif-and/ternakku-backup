<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Farm;
use App\Models\Livestock;
use App\Models\LivestockType;
use App\Enums\LivestockSexEnum;
use App\Helpers\ResponseHelper;
use App\Enums\LivestockTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Farming\PenResource;
use App\Http\Resources\LivestockListResource;

class DashboardController extends Controller
{
    public function livestockPopulationSummary()
    {
        $farm = request()->attributes->get('farm');

        $livestockTypes = LivestockType::all();
        $summary = [];

        foreach ($livestockTypes as $type) {
            $typeId = $type->id;

            $summary[$type->name] = $farm->getLivestockSummary($typeId);
        }

        return ResponseHelper::success($summary, 'Population Summary retrieved successfully');
    }

    public function getLivestock()
    {
        $farm = request()->attributes->get('farm');

        $data = LivestockListResource::collection($farm->livestocks()->get());

        return ResponseHelper::success($data, 'Livestocks retrieved successfully');
    }

}
