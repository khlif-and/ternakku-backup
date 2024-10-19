<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LivestockBirthDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'livestock_birth_id' => $this->livestock_birth_id,
            'livestock_sex_id'   => $this->livestock_sex_id,
            'livestock_sex_name' => $this->livestockSex->name,
            'weight'             => $this->weight,
            'birth_order'        => $this->birth_order,
            'status'             => $this->status,
            'offspring_value'    => (float) $this->offspring_value,
            'disease_id'         => $this->disease_id,
            'disease_name'       => $this->disease ? $this->disease->name : null,
            'indication'         => $this->indication,
        ];
    }
}
