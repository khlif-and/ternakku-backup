<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LivestockSaleWeightResource extends JsonResource
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
            'farm_id'               => $this->livestockSaleWeightH->farm_id,
            'farm_name'             => $this->livestockSaleWeightH->farm->name,
            'transaction_number'    => $this->livestockSaleWeightH->transaction_number,
            'transaction_date'      => $this->livestockSaleWeightH->transaction_date,
            'customer'              => $this->livestockSaleWeightH->customer,
            'livestock_id'          => $this->livestock_id,
            'livestock'             => new LivestockResource($this->livestock),
            'weight'                => $this->weight,
            'price_per_kg'          => $this->price_per_kg,
            'price_per_head'        => $this->price_per_head,
            'notes'                 => $this->notes,
            'created_at'            => $this->created_at->toDateTimeString(),
            'updated_at'            => $this->updated_at->toDateTimeString(),
        ];
    }
}
