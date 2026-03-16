<?php

namespace App\Services\Web\Report\NaturalInsemination\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LivestockResource;

class NaturalInseminationReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'farm_id' => $this->insemination->farm_id,
            'farm_name' => $this->insemination->farm->name ?? null,
            'transaction_number' => $this->insemination->transaction_number ?? null,
            'transaction_date' => $this->insemination->transaction_date,
            'livestock_id' => $this->reproductionCycle->livestock_id,
            'livestock' => new LivestockResource($this->reproductionCycle->livestock),
            'cost' => (float) $this->cost,
            'action_time' => $this->action_time,
            'insemination_number' => $this->insemination_number,
            'pregnant_number' => $this->pregnant_number,
            'children_number' => $this->children_number,
            'sire_breed_id' => $this->sire_breed_id,
            'sire_breed_name' => $this->sireBreed->name ?? '-',
            'sire_owner_name' => $this->sire_owner_name,
            'cycle_date' => $this->cycle_date,
            'notes' => $this->insemination->notes,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
