<?php

namespace App\Services\Web\Report\QurbanPopulation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\LivestockSexEnum;

class QurbanPopulationReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'eartag' => $this->livestockReceptionD->eartag ?? '-',
            'livestock_type_name' => $this->livestockType->name ?? '-',
            'livestock_breed_name' => $this->livestockBreed->name ?? '-',
            'sex' => $this->livestock_sex_id === LivestockSexEnum::JANTAN->value ? 'Jantan' : 'Betina',
            'age' => $this->current_age,
            'weight' => (float) $this->current_weight,
            'price' => (float) ($this->qurbanLivestock->price ?? 0),
            'status' => $this->livestockStatus->name ?? '-',
            'livestock_status_id' => $this->livestock_status_id,
        ];
    }
}
