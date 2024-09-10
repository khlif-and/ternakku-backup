<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentIndividuMedicineItemResource extends JsonResource
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
            'unit' => $this->unit,
            'qty_per_unit' => (float) $this->qty_per_unit,
            'price_per_unit' => (float) $this->price_per_unit,
            'total_price' => (float) $this->total_price,
        ];
    }
}
