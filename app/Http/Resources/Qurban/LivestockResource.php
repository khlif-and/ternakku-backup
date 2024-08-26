<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LivestockResource extends JsonResource
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
            'eartag' => $this->eartag_number,
            'current_weight' => $this->current_weight,
            'current_age' => $this->current_age,
            'livestock_type_id' => $this->livestock_type_id,
            'livestock_type_name' => $this->livestockType->name,
            'livestock_breed_id' => $this->livestock_breed_id,
            'livestock_breed_name' => $this->livestockBreed->name,
            'price' => (float) $this->qurbanLivestock->price,
            'current_photo' => $this->current_photo,
        ];
    }
}
