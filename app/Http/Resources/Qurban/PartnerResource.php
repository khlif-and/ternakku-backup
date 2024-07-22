<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
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
            'province_name' => $this->farmDetail?->province?->name,
            'regency_name' => $this->farmDetail?->regency?->name,
            'district_name' => $this->farmDetail?->district?->name,
            'village_name' => $this->farmDetail?->village?->name,
            'postal_code' => $this->farmDetail?->postal_code,
            'address_line' => $this->farmDetail?->address_line,
            'longitude' => $this->farmDetail?->longitude,
            'latitude' => $this->farmDetail?->latitude,
            'capacity' => $this->farmDetail?->capacity,
            'logo' => getNeoObject($this->farmDetail?->logo),
            'description' => $this->farmDetail?->description,
            'owner_name' => $this->owner?->name,
        ];
    }
}
