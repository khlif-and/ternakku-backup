<?php

namespace App\Services\Web\Report\MilkProductionIndividu\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MilkProductionIndividuReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->milkProductionH->transaction_number ?? '-',
            'transaction_date' => $this->milkProductionH->transaction_date ?? null,
            'milking_shift' => $this->milking_shift ?? '-',
            'milking_time' => $this->milking_time ?? '-',
            'milker_name' => $this->milker_name ?? '-',
            'livestock_name' => $this->livestock->eartag_number ?? '-',
            'livestock_code' => $this->livestock->livestockType->name ?? '-',
            'quantity_liters' => $this->quantity_liters,
            'milk_condition' => $this->milk_condition,
            'notes' => $this->notes,
        ];
    }
}
