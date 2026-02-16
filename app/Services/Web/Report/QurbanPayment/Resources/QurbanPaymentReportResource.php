<?php

namespace App\Services\Web\Report\QurbanPayment\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class QurbanPaymentReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'transaction_date' => $this->transaction_date,
            'customer_name' => $this->qurbanCustomer->user->name ?? '-',
            'amount' => $this->amount,
            'sales_transaction_number' => $this->qurbanSaleLivestockH->transaction_number ?? '-',
            'notes' => '-', // Payment table doesn't have notes column based on migration
        ];
    }
}
