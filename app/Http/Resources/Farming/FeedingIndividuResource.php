<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
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
            'livestock'           => new LivestockResource($this->livestock),

            'total_cost'          => $this->total_cost,
            'notes'               => $this->notes,

            'items'               => FeedingIndividuItemResource::collection($this->feedingIndividuItems),

            'created_at'          => $this->created_at->toDateTimeString(),
            'updated_at'          => $this->updated_at->toDateTimeString(),
        ];
    }

}
