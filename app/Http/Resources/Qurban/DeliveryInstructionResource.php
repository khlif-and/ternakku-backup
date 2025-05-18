<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryInstructionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'delivery_date' => $this->delivery_date,
            'driver_id' => $this->driver_id,
            'fleet_id' => $this->fleet_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'delivery_orders' => DeliveryOrderResource::collection($this->deliveryOrders),
        ];
    }
}
