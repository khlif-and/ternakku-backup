<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkAnalysisIndividuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'farm_id' => $this->milkAnalysisH->farm_id,
            'farm_name' => $this->milkAnalysisH->farm->name,
            'transaction_number' => $this->milkAnalysisH->transaction_number,
            'transaction_date' => $this->milkAnalysisH->transaction_date,
            'livestock_id'        => $this->livestock_id,
            'livestock'           => new LivestockResource($this->livestock),
            'bj' => $this->bj,
            'at' => (boolean) $this->at,
            'ab' => (boolean) $this->ab,
            'mbrt' => $this->mbrt,
            'a_water' => $this->a_water,
            'protein' => $this->protein,
            'fat' => $this->fat,
            'snf' => $this->snf,
            'ts' => $this->ts,
            'rzn' => $this->rzn,
            'notes' => $this->notes,
        ];
    }
}
