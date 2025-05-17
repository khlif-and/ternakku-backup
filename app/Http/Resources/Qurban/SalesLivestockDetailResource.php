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
            'weight' => (float) $this->weight,
            'price_per_kg' => (float) $this->price_per_kg,
            'price_per_head' => (float) $this->price_per_head,
            'delivery_plan_date' => $this->delivery_plan_date,
            'paid_amount' => (float) $this->livestock->qurbanPayments->sum('amount'),
            'delivery_order_id' => $this->livestock->qurbanDeliveryOrderD ?  $this->livestock->qurbanDeliveryOrderD->qurbanDeliveryOrderH->id : null,
            'delivery_schedule' =>  $this->livestock->qurbanDeliveryOrderD ?  $this->livestock->qurbanDeliveryOrderD->qurbanDeliveryOrderH->delivery_schedule : null,
        ];
    }
}
