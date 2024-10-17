<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PregnantCheckResource extends JsonResource
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
            'farm_id'               => $this->pregnantCheck->farm_id,
            'farm_name'             => $this->pregnantCheck->farm->name,
            'transaction_number'    => $this->pregnantCheck->transaction_number,
            'transaction_date'      => $this->pregnantCheck->transaction_date,
            'livestock_id'          => $this->reproductionCycle->livestock_id,
            'livestock'             => new LivestockResource($this->reproductionCycle->livestock),
            'cost'                  => (float) $this->cost,
            'action_time'           => $this->action_time,
            'officer_name'          => $this->officer_name,
            'status'                => $this->status,
            'pregnant_number'       => $this->pregnant_number,
            'children_number'       => $this->children_number,
            'estimated_birth_date'  => $this->estimated_birth_date,
            'notes'                 => $this->pregnantCheck->notes,
            'created_at'            => $this->created_at->toDateTimeString(),
            'updated_at'            => $this->updated_at->toDateTimeString(),
        ];
    }
}
