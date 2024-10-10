<?php

namespace App\Http\Controllers\Api\Farming;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Enums\LivestockSexEnum;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Farming\FemaleLivestockResource;

class ReproductionMasterController extends Controller
{
    public function getFemaleLivestockData($farmId , $livestockId)
    {
        $farm = request()->attributes->get('farm');

        $livestock = Livestock::where('farm_id' , $farmId)->findOrFail($livestockId);

        if($livestock->livestock_sex_id !== LivestockSexEnum::BETINA->value){
            return ResponseHelper::error('Livestock is not female.', 400);
        }

        return ResponseHelper::success(new FemaleLivestockResource($livestock), 'Data retrieved successfully', 200);
    }
}
