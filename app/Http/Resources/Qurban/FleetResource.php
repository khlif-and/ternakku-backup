<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FleetResource extends JsonResource
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
            'police_number' => $this->police_number,
            'photo' => getNeoObject($this->photo),
            'latest_position' => $this->whenLoaded('latestPosition', function () {
                return [
                    'latitude' => (float) $this->latestPosition->latitude ?? null,
                    'longitude' => (float) $this->latestPosition->longitude ?? null,
                ];
            }),
        ];
    }
}
