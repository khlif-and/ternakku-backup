<?php

namespace App\Services\Web\Report\MutationIndividu\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class MutationIndividuReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->mutationH->transaction_number,
            'transaction_date' => $this->mutationH->transaction_date,
            'livestock_code' => $this->livestock->eartag_number ?? '-',
            'pen_from' => $this->penFrom->name ?? '-',
            'pen_to' => $this->penTo->name ?? '-',
            'notes' => $this->notes,
        ];
    }
}
