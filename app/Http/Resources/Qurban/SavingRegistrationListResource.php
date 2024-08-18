<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SavingRegistrationListResource extends JsonResource
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
            'livestock_breed_id' => $this->livestock_breed_id,
            'livestock_breed_name' => $this->livestockBreed->name,
            'livestock_type_id' => $this->livestockBreed->livestock_type_id,
            'livestock_type_name' => $this->livestockBreed->livestockType->name,
            'farm_id' => $this->farm_id,
            'farm_name' => $this->farm->name,
            'weight' => (float) $this->weight,
            'price_per_kg' => (int) $this->price_per_kg,
            'price_total' =>  $this->weight * $this->price_per_kg,
            'postal_code' => $this->postal_code,
            'full_address' => $this->full_address,
            'duration_months' => (int) $this->duration_months,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
