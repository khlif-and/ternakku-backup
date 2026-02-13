<?php

namespace App\Services\Web\Report\FeedingColony\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedingColonyReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'feeding_h_id' => $this->feeding_h_id,
            'transaction_date' => $this->feedingH->transaction_date,
            'transaction_number' => $this->feedingH->transaction_number,
            'pen_id' => $this->pen_id,
            'pen_name' => $this->pen->name,
            'notes' => $this->notes,
            'total_livestock' => $this->total_livestock,
            'total_cost' => $this->total_cost,
            'average_cost' => $this->average_cost,
            'items' => $this->feedingColonyItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'name' => $item->name,
                    'qty_kg' => $item->qty_kg,
                    'price_per_kg' => $item->price_per_kg,
                    'total_price' => $item->total_price,
                ];
            }),
        ];
    }
}
