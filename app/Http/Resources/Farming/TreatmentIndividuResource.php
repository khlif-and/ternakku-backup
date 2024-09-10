<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Farming\TreatmentIndividuMedicineItemResource;
use App\Http\Resources\Farming\TreatmentIndividuTreatmentItemResource;

class TreatmentIndividuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'farm_id'             => $this->treatmentH->farm_id,
            'farm_name'           => $this->treatmentH->farm->name,
            'transaction_number'  => $this->treatmentH->transaction_number,
            'transaction_date'    => $this->treatmentH->transaction_date,
            'livestock_id'        => $this->livestock_id,
            'livestock'           => new LivestockResource($this->livestock),
            'disease_id'          => $this->disease_id,
            'disease_name'        => $this->disease->name,

            'total_cost'          => (float) $this->total_cost,
            'notes'               => $this->notes,

            'medicines'           => TreatmentIndividuMedicineItemResource::collection($this->treatmentIndividuMedicineItems),
            'treatments'          => TreatmentIndividuTreatmentItemResource::collection($this->treatmentIndividuTreatmentItems),

            'created_at'          => $this->created_at->toDateTimeString(),
            'updated_at'          => $this->updated_at->toDateTimeString(),
        ];
    }

}
