<?php

namespace App\Services\Web\Report\SalesOrder\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesOrderReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->qurbanSalesOrder->transaction_number ?? '-',
            'order_date' => $this->qurbanSalesOrder->order_date,
            'customer_name' => $this->qurbanSalesOrder->qurbanCustomer->user->name ?? '-',
            'livestock_type_name' => $this->livestockType->name ?? '-',
            'quantity' => $this->quantity,
            'total_weight' => (float) $this->total_weight,
        ];
    }
}
