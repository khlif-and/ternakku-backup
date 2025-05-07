<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use App\Http\Resources\Qurban\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;


class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_date' => $this->transaction_date,
            'customer' => new CustomerResource($this->qurbanCustomer),
            'livestock' => new LivestockResource($this->livestock),
            'amount' => (float) $this->amount,
            'price_per_kg' => (float) $this->livestock->qurbanSaleLivestockD->price_per_kg,
            'price_per_head' => (float) $this->livestock->qurbanSaleLivestockD->price_per_head,
            'paid_amount' => (float) $this->livestock->qurbanPayments->sum('amount'),
        ];
    }
}
