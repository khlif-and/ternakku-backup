<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'roles' => $this->roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                ];
            }),
            'profile' => [
                'photo' => $this->profile && $this->profile->photo ? getNeoObject($this->profile?->photo) : null,
            ],
            'customer_at' => $this->customer->map(fn($item) => ['farm_id' => $item->farm_id]),
            'farm_admin_at' => $this->farmAdmin()->map(fn($item) => ['farm_id' => $item->farm_id]),
            'farm_abk_at' => $this->farmAbk()->map(fn($item) => ['farm_id' => $item->farm_id]),
            'farm_driver_at' => $this->farmDriver()->map(fn($item) => ['farm_id' => $item->farm_id]),
            'farm_marketing_at' => $this->farmMarketing()->map(fn($item) => ['farm_id' => $item->farm_id]),
            'farm_owner_at' => $this->farmOwner()->map(fn($item) => ['farm_id' => $item->farm_id]),
        ];
    }
}
