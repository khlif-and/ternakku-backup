<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SavingRegistrationUserListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->userBank->user->id,
            'user_name' => $this->userBank->user->name,
            'bank_id' => $this->userBank->bank->id,
            'bank_name' => $this->userBank->bank->name,
            'account_number' => $this->userBank->account_number,
            'portion' => $this->portion,
        ];
    }
}
