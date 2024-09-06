<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedingIndividuItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type,
            'name' => $this->name,
            'qty_kg' => (float) $this->qty_kg,
            'price_per_kg' => (float) $this->price_per_kg,
            'total_price' => (float) $this->total_price,
        ];
    }
}
