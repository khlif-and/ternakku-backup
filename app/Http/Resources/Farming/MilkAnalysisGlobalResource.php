<?php

namespace App\Http\Resources\Farming;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkAnalysisGlobalResource extends JsonResource
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
            'farm_id' => $this->farm_id,
            'farm_name' => $this->farm->name,
            'transaction_number' => $this->transaction_number,
            'transaction_date' => $this->transaction_date,
            'bj' => $this->bj,
            'at' => $this->at,
            'ab' => $this->ab,
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
