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
            'farm_id' => $this->livestockReceptionD->livestockReceptionH->farm_id,
            'farm_name' => $this->livestockReceptionD->livestockReceptionH->farm->name,
            'eartag' => $this->livestockReceptionD->eartag_number,
            'current_weight' => $this->current_weight,
            'current_age' => $this->current_age,
            'livestock_type_id' => $this->livestockReceptionD->livestock_type_id,
            'livestock_type_name' => $this->livestockReceptionD->livestockType->name,
            'livestock_breed_id' => $this->livestockReceptionD->livestock_breed_id,
            'livestock_breed_name' => $this->livestockReceptionD->livestockBreed->name,
            'price' => $this->qurbanLivestock->price,
        ];
    }
}
