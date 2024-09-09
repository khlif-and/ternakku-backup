<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LivestockDeathResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'farm_id' => $this->farm_id,
            'farm_name' => $this->farm->name,
            'transaction_number' => $this->transaction_number,
            'transaction_date' => $this->transaction_date,
            'livestock_id' => $this->livestock_id,
            'livestock' => new LivestockResource($this->livestock),
            'disease_id' => $this->disease_id,
            'disease' => optional($this->disease)->name,
            'indication' => $this->indication,
            'notes' => $this->notes,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
