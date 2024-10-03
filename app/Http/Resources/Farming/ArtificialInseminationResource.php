<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtificialInseminationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'farm_id'               => $this->insemination->farm_id,
            'farm_name'             => $this->insemination->farm->name,
            'transaction_number'    => $this->insemination->transaction_number,
            'transaction_date'      => $this->insemination->transaction_date,
            'livestock_id'          => $this->reproductionCycle->livestock_id,
            'livestock'             => new LivestockResource($this->reproductionCycle->livestock),
            'cost'                  => (float) $this->cost,
            'action_time'           => $this->action_time,
            'officer_name'          => $this->officer_name,
            'insemination_number'   => $this->insemination_number,
            'pregnant_number'       => $this->pregnant_number,
            'children_number'       => $this->children_number,
            'semen_breed_id'        => $this->semen_breed_id,
            'semen_breed_name'      => $this->semenBreed->name,
            'sire_name'             => $this->sire_name,
            'semen_producer'        => $this->semen_producer,
            'semen_batch'           => $this->semen_batch,
            'cycle_date'            => $this->cycle_date,
            'notes'                 => $this->insemination->notes,
            'created_at'            => $this->created_at->toDateTimeString(),
            'updated_at'            => $this->updated_at->toDateTimeString(),
        ];
    }
}
