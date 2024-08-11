<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Qurban\SavingRegistrationDetailResource;

class ContractDetailResource extends JsonResource
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
            'livestock_breed_id' => $this->livestock_breed_id,
            'livestock_breed_name' => $this->livestockBreed->name,
            'livestock_type_id' => $this->livestockBreed->livestock_type_id,
            'livestock_type_name' => $this->livestockBreed->livestockType->name,
            'farm_id' => $this->farm_id,
            'farm_name' => $this->farm->name,
            'weight' => (float) $this->weight,
            'price_per_kg' => (int) $this->price_per_kg,
            'price_total' =>  $this->weight * $this->price_per_kg,
            'province_id' => (int) $this->province_id,
            'province_name' => $this->province?->name,
            'regency_id' => (int) $this->regency_id,
            'regency_name' => $this->regency?->name,
            'district_id' => (int) $this->district_id,
            'district_name' => $this->district?->name,
            'village_id' => (int) $this->village_id,
            'village_name' => $this->village?->name,
            'postal_code' => $this->postal_code,
            'address_line' => $this->address_line,
            'contract_date' => $this->contract_date,
            'down_payment' => $this->down_payment,
            'estimated_delivery_date' => $this->estimated_delivery_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'saving_registration' => new SavingRegistrationDetailResource($this->qurbanSavingRegistration)
        ];
    }
}
