<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
                $createdAt = $this->latestPosition->created_at;

                // Cek apakah created_at lebih dari 24 jam dari sekarang
                $isActive = false;
                if ($createdAt) {
                    $isActive = Carbon::now()->diffInHours($createdAt) <= 24;
                }

                return [
                    'latitude' => (float) $this->latestPosition->latitude ?? null,
                    'longitude' => (float) $this->latestPosition->longitude ?? null,
                    'is_active' => $isActive,
                ];
            }),

        ];
    }
}
