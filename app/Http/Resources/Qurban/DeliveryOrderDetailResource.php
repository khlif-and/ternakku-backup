<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use App\Http\Resources\FarmDetailResource;
use App\Http\Resources\Qurban\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Qurban\CustomerAddressResource;

class DeliveryOrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'livestock' => new LivestockResource($this->livestock),
            'weight' => (float) $this->qurbanSaleLivestockD->weight,
            'price_per_kg' => (float) $this->qurbanSaleLivestockD->price_per_kg,
            'price_per_head' => (float) $this->qurbanSaleLivestockD->price_per_head,
            'delivery_plan_date' => $this->qurbanSaleLivestockD->delivery_plan_date,
            'paid_amount' => (float) $this->livestock->qurbanPayments->sum('amount'),
        ];
    }
}
