<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerDetailResource extends JsonResource
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
            'full_address' => $this->full_address,
            'longitude' => $this->farmDetail?->longitude,
            'latitude' => $this->farmDetail?->latitude,
            'description' => $this->farmDetail?->description,
            'logo' => getNeoObject($this->farmDetail?->logo),
            'pen_hoto' => $this->pens->first()?->photo
        ];
    }
}
