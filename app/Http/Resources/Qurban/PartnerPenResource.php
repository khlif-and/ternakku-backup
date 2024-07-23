<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerPenResource extends JsonResource
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
            'name' => $this->name,
            'photo' => $this->photo,
            'capacity' => $this->capacity,
            'population' => $this->population,
        ];
    }
}
