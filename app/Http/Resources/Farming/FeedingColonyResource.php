<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use App\Http\Resources\Farming\PenResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Farming\FeedingColonyItemResource;

class FeedingColonyResource extends JsonResource
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
            'pen_id'              => $this->pen_id,
            'pen'                 => new PenResource($this->pen),

            'total_livestock'     => $this->total_livestock,
            'total_cost'          => $this->total_cost,
            'average_cost'        => $this->average_cost,
            'notes'               => $this->notes,

            'items'               => FeedingColonyItemResource::collection($this->feedingColonyItems),
            'livestocks'          => LivestockResource::collection($this->livestocks),

            'created_at'          => $this->created_at->toDateTimeString(),
            'updated_at'          => $this->updated_at->toDateTimeString(),
        ];
    }

}
