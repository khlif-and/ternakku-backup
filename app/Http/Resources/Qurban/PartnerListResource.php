<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerListResource extends JsonResource
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
            'logo' => getNeoObject($this->farmDetail?->logo),
        ];
    }
}
