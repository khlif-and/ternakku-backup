<?php

namespace App\Services\Web\Report\PregnancyCheck\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LivestockResource;

class PregnancyCheckReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->pregnantCheck->transaction_number,
            'transaction_date' => $this->pregnantCheck->transaction_date,
            'livestock' => (new LivestockResource($this->reproductionCycle->livestock))->resolve(),
            'action_time' => $this->action_time,
            'officer_name' => $this->officer_name,
            'pregnant_number' => $this->pregnant_number,
            'children_number' => $this->children_number,
            'status' => $this->status,
            'result_text' => $this->status === 'PREGNANT' ? 'Bunting' : 'Tidak Bunting',
            'pregnant_age' => $this->pregnant_age,
            'estimated_birth_date' => $this->estimated_birth_date,
            'cost' => (float) $this->cost,
            'notes' => $this->pregnantCheck->notes,
        ];
    }
}
