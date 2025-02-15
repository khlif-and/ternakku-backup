<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use App\Http\Resources\FarmDetailResource;
use App\Http\Resources\Qurban\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Qurban\SalesOrderResource;
use App\Http\Resources\Qurban\SalesLivestockDetailResource;

class SalesLivestockResource extends JsonResource
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
            'transaction_number' => $this->transaction_number,
            'farm' => new FarmDetailResource($this->farm),
            'customer' => new CustomerResource($this->qurbanCustomer),
            'sales_order' => new SalesOrderResource($this->qurbanSalesOrder),
            'details' => SalesLivestockDetailResource::collection($this->qurbanSalesLivestockD),
        ];
    }
}
