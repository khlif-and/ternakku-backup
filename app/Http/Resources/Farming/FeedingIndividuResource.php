<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockListResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedingIndividuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'farm_id'             => $this->feedingH->farm_id,
            'farm_name'           => $this->feedingH->farm->name,
            'transaction_number'  => $this->feedingH->transaction_number,
            'transaction_date'    => $this->feedingH->transaction_date,
            'livestock_id'        => $this->livestock_id,
            'livestock'           => new LivestockListResource($this->livestock),

            // Forage details
            'forage_name'         => $this->forage_name,
            'forage_qty_kg'       => $this->forage_qty_kg,
            'forage_price_kg'     => $this->forage_price_kg,
            'forage_total'        => $this->forage_total,

            // Concentrate details
            'concentrate_name'    => $this->concentrate_name,
            'concentrate_qty_kg'  => $this->concentrate_qty_kg,
            'concentrate_price_kg'=> $this->concentrate_price_kg,
            'concentrate_total'   => $this->concentrate_total,

            // Feed ingredient details
            'ingredient_name'     => $this->ingredient_name,
            'ingredient_qty_kg'   => $this->ingredient_qty_kg,
            'ingredient_price_kg' => $this->ingredient_price_kg,
            'ingredient_total'    => $this->ingredient_total,

            // Additional fields
            'total_cost'          => $this->total_cost,
            'notes'               => $this->notes,

            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
        ];
    }

}
