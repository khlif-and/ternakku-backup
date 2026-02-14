<?php

namespace App\Services\Web\Report\MilkAnalysisGlobal\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MilkAnalysisGlobalReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'transaction_date' => $this->transaction_date,
            'bj' => $this->bj,
            'at' => $this->at ? 'Positif' : 'Negatif',
            'ab' => $this->ab ? 'Positif' : 'Negatif',
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
