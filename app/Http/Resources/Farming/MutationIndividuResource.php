<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use App\Http\Resources\Farming\PenResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MutationIndividuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'farm_id'             => $this->mutationH->farm_id,
            'farm_name'           => $this->mutationH->farm->name,
            'transaction_number'  => $this->mutationH->transaction_number,
            'transaction_date'    => $this->mutationH->transaction_date,
            'livestock_id'        => $this->livestock_id,
            'livestock'           => new LivestockResource($this->livestock),
            'from'                => $this->from,
            'pen_from'            => new PenResource($this->penFrom),
            'to'                  => $this->to,
            'pen_to'              => new PenResource($this->penTo),
            'notes'               => $this->notes,
            'created_at'          => $this->created_at->toDateTimeString(),
            'updated_at'          => $this->updated_at->toDateTimeString(),
        ];
    }
}
