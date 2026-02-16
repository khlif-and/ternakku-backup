<?php

namespace App\Services\Web\Report\QurbanDeliveryOrder\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class QurbanDeliveryOrderReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'transaction_date' => $this->transaction_date,
            'customer_name' => $this->qurbanCustomerAddress->qurbanCustomer->name ?? '-',
            'address' => $this->qurbanCustomerAddress->address ?? '-',
            'status' => $this->status,
            'file' => $this->file,
            'receipt_at' => $this->receipt_at,
        ];
    }
}
