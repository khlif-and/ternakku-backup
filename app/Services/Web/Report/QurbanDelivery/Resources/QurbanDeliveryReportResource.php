<?php

namespace App\Services\Web\Report\QurbanDelivery\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\FarmDetailResource;
use App\Http\Resources\Qurban\FleetResource;
use App\Http\Resources\Qurban\DeliveryOrderResource;
use Carbon\Carbon;

class QurbanDeliveryReportResource extends JsonResource
{
    public function toArray($request)
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
