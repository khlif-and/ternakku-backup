<?php

namespace App\Helpers\web;

use App\Models\Farm;
use App\Models\Livestock;
use App\Models\LivestockBreed;

class ArtificialInseminationFormService
{
    public function getDropdownData(Farm $farm): array
    {
        return [
            'livestocks' => Livestock::where('farm_id', $farm->id)
                ->whereHas('livestockSex', function ($q) {
                    $q->where('name', 'Female')->orWhere('name', 'Betina');
                })->get(),
            'breeds' => LivestockBreed::all(),
        ];
    }
}
