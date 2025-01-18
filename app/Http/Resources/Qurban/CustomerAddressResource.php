<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAddressResource extends JsonResource
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
            'description' => $this->description,
            'region_id' => $this->region_id,
            'region_name' => $this->region->name,
            'postal_code' => $this->postal_code,
            'address_line' => $this->address_line,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
        ];
    }
}
