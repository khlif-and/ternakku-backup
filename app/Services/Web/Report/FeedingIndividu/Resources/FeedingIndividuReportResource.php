<?php

namespace App\Services\Web\Report\FeedingIndividu\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class FeedingIndividuReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_date' => $this->feedingH->transaction_date,
            'livestock_name' => $this->livestock->name ?? '-',
            'eartag' => $this->livestock->eartag ?? $this->livestock->eartag_number ?? '-',
            'pen_name' => $this->livestock->pen->name ?? '-',
            'total_cost' => $this->items_sum_total_price ?? $this->feedingIndividuItems->sum('total_price'),
            'items' => $this->feedingIndividuItems->map(function ($item) {
                return [
                    'name' => $item->feedItem->name ?? $item->name, // Adjust based on relation or column
                    'type' => $item->type, // concentrate/forage
                    'qty_kg' => $item->qty_kg,
                    'price_per_kg' => $item->price_per_kg,
                    'total_price' => $item->total_price,
                ];
            }),
        ];
    }
}
