<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use App\Http\Resources\Qurban\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;


class SaleLivestockPaymentResource extends JsonResource
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
            'transaction_number' => $this->transaction_number,
            'transaction_date' => $this->transaction_date,
            'customer' => new CustomerResource($this->qurbanCustomer),
            'amount' => (float) $this->amount,
            'sale_livestock' => new SalesLivestockResource($this->qurbanSaleLivestockH),
        ];
    }
}
