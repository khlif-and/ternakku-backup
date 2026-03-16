<?php

namespace App\Services\Web\Report\MilkProductionGlobal\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MilkProductionGlobalReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
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
