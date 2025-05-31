<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\FarmDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryInstructionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'farm' => new FarmDetailResource($this->farm),
            'delivery_date' => $this->delivery_date,
            'driver' => new UserResource($this->driver),
            'fleet' => new FleetResource($this->fleet),
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'delivery_orders' => DeliveryOrderResource::collection($this->deliveryOrders),
        ];
    }
}
