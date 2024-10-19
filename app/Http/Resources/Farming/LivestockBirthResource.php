<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Farming\LivestockBirthDetailResource;

class LivestockBirthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'farm_id'              => $this->farm_id,
            'farm_name'            => $this->farm->name,
            'transaction_number'   => $this->transaction_number,
            'transaction_date'     => $this->transaction_date,
            'livestock_id'         => $this->reproductionCycle->livestock_id,
            'livestock'            => new LivestockResource($this->reproductionCycle->livestock),
            'officer_name'         => $this->officer_name,
            'cost'                 => (float) $this->cost,
            'status'               => $this->status,
            'estimated_weaning'    => $this->estimated_weaning,
            'notes'                => $this->notes,

            'details'              => LivestockBirthDetailResource::collection($this->livestockBirthD),

            'created_at'           => $this->created_at->toDateTimeString(),
            'updated_at'           => $this->updated_at->toDateTimeString(),
        ];
    }
}
