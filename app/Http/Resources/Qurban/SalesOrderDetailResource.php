<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use App\Http\Resources\Qurban\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesOrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'quantity' => (float) $this->quantity,
            'total_weight' => (float) $this->total_weight,
            'livestock_type_id' => $this->livestock_type_id,
            'livestock_type_name' => $this->livestockType->name,
        ];
    }
}
