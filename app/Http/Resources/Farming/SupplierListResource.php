<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierListResource extends JsonResource
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
            'phone_number' => $this->phone_number,
            'region_id' => $this->region_id,
            'region_name' => $this->region->name,
            'postal_code' => $this->postal_code,
            'address_line' => $this->address_line,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
        ];
    }
}
