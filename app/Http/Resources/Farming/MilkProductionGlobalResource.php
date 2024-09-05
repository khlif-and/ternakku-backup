<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkProductionGlobalResource extends JsonResource
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
            'farm_id' => $this->farm_id,
            'farm_name' => $this->farm->name,
            'transaction_number' => $this->transaction_number,
            'transaction_date' => $this->transaction_date,
            'milking_shift' => $this->milking_shift,
            'milking_time' => $this->milking_time,
            'milker_name' => $this->milker_name,
            'quantity_liters' => $this->quantity_liters,
            'milk_condition' => $this->milk_condition,
            'notes' => $this->notes,
        ];
    }
}
