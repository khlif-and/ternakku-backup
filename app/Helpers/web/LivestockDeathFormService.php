<?php

namespace App\Helpers\web;

use App\Models\Farm;
use App\Models\Livestock;
use App\Models\Disease;
use App\Enums\LivestockStatusEnum;

class LivestockDeathFormService
{
    /**
     * Get dropdown data for death form (create)
     */
    public function getDropdownData(Farm $farm): array
    {
        return [
            'livestocks' => Livestock::with(['livestockType', 'livestockBreed'])
                ->where('farm_id', $farm->id)
                ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
                ->get(),
            'diseases' => Disease::pluck('name', 'id')->toArray(),
        ];
    }

    /**
     * Get dropdown data for death form (edit - includes currently selected livestock)
     */
    public function getDropdownDataForEdit(Farm $farm, $currentLivestockId): array
    {
        return [
            'livestocks' => Livestock::with(['livestockType', 'livestockBreed'])
                ->where('farm_id', $farm->id)
                ->where(function ($q) use ($currentLivestockId) {
                    $q->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
                        ->orWhere('id', $currentLivestockId);
                })
                ->get(),
            'diseases' => Disease::pluck('name', 'id')->toArray(),
        ];
    }
}
