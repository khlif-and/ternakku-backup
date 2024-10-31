<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LivestockReweightResource extends JsonResource
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
            'farm_id'               => $this->livestockReweightH->farm_id,
            'farm_name'             => $this->livestockReweightH->farm->name,
            'transaction_number'    => $this->livestockReweightH->transaction_number,
            'transaction_date'      => $this->livestockReweightH->transaction_date,
            'livestock_id'          => $this->livestock_id,
            'livestock'             => new LivestockResource($this->livestock),
            'weight'                => (float) $this->weight,
            'photo'                 => $this->photo ? getNeoObject($this->photo) : null,
            'notes'                 => $this->livestockReweightH->notes,
            'created_at'            => $this->created_at->toDateTimeString(),
            'updated_at'            => $this->updated_at->toDateTimeString(),
        ];
    }
}
