<?php

namespace App\Services\Web\Report\Birth\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LivestockResource;

class BirthReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->livestockBirth->transaction_number,
            'transaction_date' => $this->livestockBirth->transaction_date,
            'mother_livestock' => (new LivestockResource($this->livestockBirth->reproductionCycle->livestock))->resolve(),
            'birth_order' => $this->birth_order,
            'livestock_sex' => $this->livestockSex->name ?? '-',
            'weight' => (float) $this->weight,
            'status' => $this->status,
            'status_text' => $this->status === 'alive' ? 'Hidup' : 'Mati',
            'disease' => $this->disease->name ?? '-',
            'indication' => $this->indication,
            'officer_name' => $this->livestockBirth->officer_name,
            'notes' => $this->livestockBirth->notes,
        ];
    }
}
