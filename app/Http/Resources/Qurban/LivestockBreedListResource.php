<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LivestockBreedListResource extends JsonResource
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
            'livestock_type_id' => $this->livestock_type_id,
            'livestock_type_name' => $this->livestockType?->name,
            'name' => $this->name,
            'photo' => getNeoObject($this->photo),
        ];
    }
}
