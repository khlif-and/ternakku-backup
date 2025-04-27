<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\FarmListResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->user_id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone_number' => $this->user->phone_number,
            'profile' => [
                'photo' => $this->user->profile && $this->user->profile->photo ? getNeoObject($this->user->profile?->photo) : null,
            ],
            'farm_role' => $this->farm_role
        ];
    }
}
