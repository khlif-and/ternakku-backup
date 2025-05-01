<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class PriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'farm_id'           => $this->farm_id,
            'farm_name'         => $this->farm->name ?? null,
            'hijri_year'        => $this->hijri_year,
            'livestock_type_id' => $this->livestock_type_id,
            'livestock_type'    => $this->livestockType->name ?? null,
            'name'              => $this->name,
            'start_weight'      => (float) $this->start_weight,
            'end_weight'        => (float) $this->end_weight,
            'price_per_kg'      => (float) $this->price_per_kg,
        ];
    }
}
