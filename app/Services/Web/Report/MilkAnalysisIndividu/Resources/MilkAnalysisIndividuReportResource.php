<?php

namespace App\Services\Web\Report\MilkAnalysisIndividu\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class MilkAnalysisIndividuReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_date' => $this->milkAnalysisH->transaction_date,
            'livestock_name' => $this->livestock->eartag_number ?? '-',
            'livestock_code' => $this->livestock->livestockType->name ?? '-',
            'bj' => $this->bj,
            'fat' => $this->fat,
            'snf' => $this->snf,
            'protein' => $this->protein,
            'ts' => $this->ts,
            'mbrt' => $this->mbrt,
            'at' => $this->at ? 'Positif' : 'Negatif',
            'ab' => $this->ab ? 'Positif' : 'Negatif',
            'notes' => $this->notes,
        ];
    }
}
