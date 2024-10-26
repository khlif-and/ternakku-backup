<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use App\Http\Resources\Farming\PenResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Farming\TreatmentColonyMedicineItemResource;
use App\Http\Resources\Farming\TreatmentColonyTreatmentItemResource;

class TreatmentColonyResource extends JsonResource
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
            'pen_id'              => $this->pen_id,
            'pen'                 => new PenResource($this->pen),
            'disease_id'          => $this->disease_id,
            'disease_name'        => $this->disease->name,

            'total_cost'          => (float) $this->total_cost,
            'average_cost'        => (float) $this->average_cost,
            'notes'               => $this->notes,

            'medicines'           => TreatmentColonyMedicineItemResource::collection($this->treatmentColonyMedicineItems),
            'treatments'          => TreatmentColonyTreatmentItemResource::collection($this->treatmentColonyTreatmentItems),

            'livestocks'          => LivestockResource::collection($this->livestocks),

            'created_at'          => $this->created_at->toDateTimeString(),
            'updated_at'          => $this->updated_at->toDateTimeString(),
        ];
    }

}
