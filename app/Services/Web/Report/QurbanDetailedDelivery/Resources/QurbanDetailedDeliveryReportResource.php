<?php

namespace App\Services\Web\Report\QurbanDetailedDelivery\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\FarmDetailResource;
use App\Http\Resources\Qurban\FleetResource;
use Carbon\Carbon;

class QurbanDetailedDeliveryReportResource extends JsonResource
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
            'delivery_orders' => $this->deliveryOrders->map(function ($order) {
                $address = $order->qurbanCustomerAddress;
                $customer = $address ? $address->qurbanCustomer : null;
                $user = $customer ? $customer->user : null;

                return [
                    'id' => $order->id,
                    'transaction_number' => $order->transaction_number,
                    'status' => $order->status,
                    'recipient_name' => $address->name ?? $user->name ?? '-',
                    'recipient_address' => $address ? $address->fullAddress() : '-',
                    'recipient_phone' => $user->phone_number ?? '-',
                    'livestock_count' => $order->qurbanDeliveryOrderD->count(),
                ];
            }),
        ];
    }
}
