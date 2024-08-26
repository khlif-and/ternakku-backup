<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LivestockReceptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'farm_id'               => $this->livestockReceptionH->farm_id,
            'farm_name'             => $this->livestockReceptionH->farm->name,
            'transaction_number'    => $this->livestockReceptionH->transaction_number,
            'transaction_date'      => $this->livestockReceptionH->transaction_date,
            'supplier'              => $this->livestockReceptionH->supplier,
            'eartag_number'         => $this->eartag_number,
            'rfid_number'           => $this->rfid_number,
            'livestock_type_id'     => $this->livestock_type_id,
            'livestock_type_name'   => $this->livestockType->name ?? null,
            'livestock_group_id'    => $this->livestock_group_id,
            'livestock_group_name'  => $this->livestockGroup->name ?? null,
            'livestock_breed_id'    => $this->livestock_breed_id,
            'livestock_breed_name'  => $this->livestockBreed->name ?? null,
            'livestock_sex_id'      => $this->livestock_sex_id,
            'livestock_sex_name'    => $this->livestockSex->name ?? null,
            'pen_id'                => $this->pen_id,
            'pen_name'              => $this->pen->name ?? null,
            'age_years'             => $this->age_years,
            'age_months'            => $this->age_months,
            'weight'                => $this->weight,
            'price_per_kg'          => $this->price_per_kg,
            'price_per_head'        => $this->price_per_head,
            'notes'                 => $this->notes,
            'photo_url'             => $this->photo ? getNeoObject($this->photo) : null,
            'created_at'            => $this->created_at->toDateTimeString(),
            'updated_at'            => $this->updated_at->toDateTimeString(),
        ];
    }
}
