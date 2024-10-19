<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentScheduleIndividuResource extends JsonResource
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
            'farm_id'             => $this->treatmentSchedule->farm_id,
            'farm_name'           => $this->treatmentSchedule->farm->name,
            'transaction_number'  => $this->treatmentSchedule->transaction_number,
            'transaction_date'    => $this->treatmentSchedule->transaction_date,
            'schedule_date'       => $this->schedule_date,
            'livestock_id'        => $this->livestock_id,
            'livestock'           => new LivestockResource($this->livestock),
            'medicine_name'       => $this->medicine_name,
            'medicine_unit'       => $this->medicine_unit,
            'medicine_qty_per_unit' => $this->medicine_qty_per_unit,
            'treatment_name'      => $this->treatment_name,
            'notes'               => $this->notes,
            'created_at'          => $this->created_at->toDateTimeString(),
            'updated_at'          => $this->updated_at->toDateTimeString(),
        ];
    }

}
