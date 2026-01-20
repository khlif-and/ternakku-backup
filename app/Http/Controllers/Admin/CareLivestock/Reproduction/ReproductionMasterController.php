<?php

namespace App\Http\Controllers\Admin\CareLivestock\Reproduction;

use App\Models\Livestock;
use App\Enums\LivestockSexEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Farming\FemaleLivestockResource;

class ReproductionMasterController extends Controller
{
    public function getFemaleLivestockData($farmId, $livestockId)
    {
        $farm = request()->attributes->get('farm');

        $livestock = Livestock::where('farm_id', $farmId)->findOrFail($livestockId);

        if ($livestock->livestock_sex_id !== LivestockSexEnum::BETINA->value) {
            return redirect()->back()->withErrors(['error' => 'Livestock bukan betina.']);
        }

        $femaleData = (new FemaleLivestockResource($livestock))->toArray(request());

        return view('admin.care-livestock.reproduction.female.show', [
            'farm' => $farm,
            'livestock' => $livestock,
            'femaleData' => $femaleData,
        ]);
    }
}
