<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\PenResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmDetailResource extends JsonResource
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
            'registration_date' => $this->registration_date,
            'region_id' => $this->farmDetail?->region_id,
            'region_name' => $this->farmDetail?->region->name,
            'postal_code' => $this->farmDetail?->postal_code,
            'address_line' => $this->farmDetail?->address_line,
            'longitude' => $this->farmDetail?->longitude,
            'latitude' => $this->farmDetail?->latitude,
            'capacity' => $this->farmDetail?->capacity,
            'logo' => getNeoObject($this->farmDetail?->logo),
            'cover_photo' => getNeoObject($this->farmDetail?->cover_photo),
            'description' => $this->farmDetail?->description,
            'owner_name' => $this->owner?->name,
        ];
    }
}
