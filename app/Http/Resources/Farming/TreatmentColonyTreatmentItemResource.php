<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentColonyTreatmentItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'cost' => (float) $this->cost,
            'average_cost' => (float) ($this->cost / $this->treatmentColonyD->total_livestock),
        ];
    }
}
