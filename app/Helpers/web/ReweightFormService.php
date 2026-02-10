<?php

namespace App\Helpers\Web;

use App\Models\Farm;
use App\Models\Livestock;
use App\Enums\LivestockStatusEnum;

class ReweightFormService
{
    /**
     * Get dropdown data for reweight form (livestock list)
     */
    public function getDropdownData(Farm $farm): array
    {
        return [
            'livestocks' => Livestock::where('farm_id', $farm->id)
                ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
                ->get(),
        ];
    }
}
