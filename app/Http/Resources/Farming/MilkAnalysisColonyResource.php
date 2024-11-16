<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use App\Http\Resources\LivestockResource;
use App\Http\Resources\Farming\PenResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkAnalysisColonyResource extends JsonResource
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
            'pen_id'              => $this->pen_id,
            'pen'                 => new PenResource($this->pen),
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
            'total_livestock' => $this->total_livestock,
            'livestocks'          => LivestockResource::collection($this->livestocks),
            'notes' => $this->notes,
        ];
    }
}
