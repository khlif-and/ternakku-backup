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
        // asumsi 'farm' sudah di-inject via middleware sebelumnya
        $farm = request()->attributes->get('farm');

        // pastikan ternak milik farm yang benar
        $livestock = Livestock::where('farm_id', $farmId)->findOrFail($livestockId);

        if ($livestock->livestock_sex_id !== LivestockSexEnum::BETINA->value) {
            // web-style feedback
            return redirect()->back()->withErrors(['error' => 'Livestock bukan betina.']);
        }

        // kalau kamu masih pengin bentuk data yang rapi, pakai Resource → array
        $femaleData = (new FemaleLivestockResource($livestock))->toArray(request());

        // arahkan ke Blade yang konsisten dengan folder Admin/CareLivestock/Reproduction
        return view('admin.care-livestock.reproduction.female.show', [
            'farm' => $farm,
            'livestock' => $livestock,
            'femaleData' => $femaleData,
        ]);
    }
}
