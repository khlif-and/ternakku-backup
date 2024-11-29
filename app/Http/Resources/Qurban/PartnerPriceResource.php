<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerPriceResource extends JsonResource
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
            'livestock_type_id' => $this->livestock_type_id,
            'livestock_type' => $this->livestockType->name,
            'order' => $this->order,
            'name' => $this->name,
            'start_weight' => $this->start_weight,
            'end_weight' => $this->end_weight,
            'price_per_kg' => $this->price_per_kg,
            'previous_price_per_kg' => $this->previous_price_per_kg,
            'discount_percent' => $this->discount_percent,
            'price_per_kg_app' => $this->previous_price_per_kg - $this->discount_percent *  $this->previous_price_per_kg / 100,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
