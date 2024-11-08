<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedingColonyItemResource extends JsonResource
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
            'average_kg' => (float) ($this->qty_kg   / $this->feedingColonyD->total_livestock),
            'price_per_kg' => (float) $this->price_per_kg,
            'total_price' => (float) $this->total_price,
            'average_price' => (float) ($this->total_price / $this->feedingColonyD->total_livestock),
        ];
    }
}
