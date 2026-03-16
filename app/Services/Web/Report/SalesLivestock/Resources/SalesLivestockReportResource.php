<?php

namespace App\Services\Web\Report\SalesLivestock\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class SalesLivestockReportResource extends JsonResource
{
    public function toArray($request)
    {
        $totalAmount = 0;
        $details = $this->qurbanSaleLivestockD->map(function ($detail) use (&$totalAmount) {
            $amount = ($detail->price_per_head ?? 0) + (($detail->price_per_kg ?? 0) * ($detail->weight ?? 0));
            $totalAmount += $amount;

            return [
                'livestock_name' => $detail->livestock->eartag_number ?? '-',
                'weight' => $detail->weight ?? 0,
                'price_per_head' => $detail->price_per_head ?? 0,
                'price_per_kg' => $detail->price_per_kg ?? 0,
                'amount' => $amount,
            ];
        });

        return [
            'id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'transaction_date' => $this->transaction_date,
            'customer_name' => $this->qurbanCustomer->user->name ?? '-',
            'details' => $details,
            'total_amount' => $totalAmount,
            'notes' => $this->notes,
        ];
    }
}
