<?php

namespace App\Services\Web\Report\QurbanReception\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QurbanReceptionReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'transaction_date' => $this->transaction_date,
            'supplier' => $this->supplier,
            'notes' => $this->notes,
            'livestock_items' => $this->livestockReceptionD->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'eartag_number' => $detail->eartag_number,
                    'livestock_type' => $detail->livestockType->name ?? '-',
                    'livestock_breed' => $detail->livestockBreed->name ?? '-',
                    'livestock_sex' => $detail->livestockSex->name ?? '-',
                    'pen' => $detail->pen->name ?? '-',
                    'age_years' => $detail->age_years,
                    'age_months' => $detail->age_months,
                    'weight' => $detail->weight,
                    'price_per_kg' => $detail->price_per_kg,
                    'price_per_head' => $detail->price_per_head,
                    'qurban_price' => $detail->livestock->qurbanLivestock->price ?? null,
                ];
            }),
            'total_livestock' => $this->livestockReceptionD->count(),
        ];
    }
}
