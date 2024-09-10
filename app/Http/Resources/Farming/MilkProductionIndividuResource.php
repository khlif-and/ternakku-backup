<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkProductionIndividuResource extends JsonResource
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
            'livestock_id'        => $this->livestock_id,
            'livestock'           => new LivestockResource($this->livestock),
            'milking_shift' => $this->milking_shift,
            'milking_time' => $this->milking_time,
            'milker_name' => $this->milker_name,
            'quantity_liters' => $this->quantity_liters,
            'milk_condition' => $this->milk_condition,
            'notes' => $this->notes,
        ];
    }
}
