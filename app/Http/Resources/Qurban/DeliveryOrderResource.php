<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use App\Http\Resources\FarmDetailResource;
use App\Http\Resources\Qurban\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Qurban\CustomerAddressResource;

class DeliveryOrderResource extends JsonResource
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
            'file' => getNeoObject($this->file),
            'delivery_schedule' => $this->delivery_schedule,
            'status' => $this->status,
            'receipt_photo' => getNeoObject($this->receipt_photo),
            'receipt_at' => $this->receipt_at,
            'farm' => new FarmDetailResource($this->farm),
            'customer' => new CustomerResource($this->qurbanCustomerAddress->qurbanCustomer),
            'address' => new CustomerAddressResource($this->qurbanCustomerAddress),
            'details' => DeliveryOrderDetailResource::collection($this->qurbanDeliveryOrderD),
        ];
    }
}
