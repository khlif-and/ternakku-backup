<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use App\Http\Resources\FarmDetailResource;
use App\Http\Resources\Qurban\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Qurban\SalesOrderResource;
use App\Http\Resources\Qurban\CustomerAddressResource;
use App\Models\Livestock;

class SalesLivestockDetailResource extends JsonResource
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
            'customer_address' => new CustomerAddressResource($this->qurbanCustomerAddress),
            'livestock' => new LivestockResource($this->livestock),
            'min_weight' => $this->min_weight,
            'max_weight' => $this->max_weight,
            'price_per_kg' => $this->price_per_kg,
            'price_per_head' => $this->price_per_head,
            'paid_amount' => $this->livestock->qurbanPayments->sum('amount'),
        ];
    }
}
