<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use App\Http\Resources\Farming\PenResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkProductionColonyResource extends JsonResource
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
            'farm_id' => $this->milkProductionH->farm_id,
            'farm_name' => $this->milkProductionH->farm->name,
            'transaction_number' => $this->milkProductionH->transaction_number,
            'transaction_date' => $this->milkProductionH->transaction_date,
            'pen_id'              => $this->pen_id,
            'pen'                 => new PenResource($this->pen),
            'milking_shift' => $this->milking_shift,
            'milking_time' => $this->milking_time,
            'milker_name' => $this->milker_name,
            'quantity_liters' => $this->quantity_liters,
            'average_liters' => $this->average_liters,
            'milk_condition' => $this->milk_condition,
            'livestocks'          => LivestockResource::collection($this->livestocks),
            'notes' => $this->notes,
        ];
    }
}
