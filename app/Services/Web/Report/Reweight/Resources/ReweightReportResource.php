<?php

namespace App\Services\Web\Report\Reweight\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LivestockResource;

class ReweightReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'farm_id' => $this->livestockReweightH->farm_id,
            'farm_name' => $this->livestockReweightH->farm->name ?? null,
            'transaction_number' => $this->livestockReweightH->transaction_number,
            'transaction_date' => $this->livestockReweightH->transaction_date,
            'livestock_id' => $this->livestock_id,
            'livestock' => (new LivestockResource($this->livestock))->resolve(),
            'weight' => (float) $this->weight,
            'photo' => $this->photo ? getNeoObject($this->photo) : null,
            'notes' => $this->livestockReweightH->notes,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
