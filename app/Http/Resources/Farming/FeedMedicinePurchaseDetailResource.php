<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedMedicinePurchaseDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'purchase_type' => $this->purchase_type,
            'item_name' => $this->item_name,
            'quantity' => (float) $this->quantity,
            'unit' => $this->unit,
            'price_per_unit' => (float) $this->price_per_unit,
            'total_price' => (float) $this->total_price,
        ];
    }
}
