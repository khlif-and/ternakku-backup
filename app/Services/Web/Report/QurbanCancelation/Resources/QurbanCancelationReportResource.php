<?php

namespace App\Services\Web\Report\QurbanCancelation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class QurbanCancelationReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'transaction_date' => $this->order_date,
            'customer_name' => $this->qurbanCustomer->user->name ?? '-',
            'reason' => $this->description ?? '-',
            'status' => $this->status,
        ];
    }
}
