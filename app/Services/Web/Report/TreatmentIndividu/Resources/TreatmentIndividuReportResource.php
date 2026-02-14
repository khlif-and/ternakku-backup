<?php

namespace App\Services\Web\Report\TreatmentIndividu\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentIndividuReportResource extends JsonResource
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
            'transaction_date' => $this->treatmentH->transaction_date,
            'livestock_id' => $this->livestock->livestock_code ?? '-',
            'livestock_name' => $this->livestock->name ?? '-',
            'eartag' => $this->livestock->eartag ?? '-',
            'pen_name' => $this->livestock->pen->name ?? '-',
            'disease_name' => $this->disease->name ?? '-',
            'medicine_items' => $this->treatmentIndividuMedicineItems->map(fn($item) => [
                'name' => $item->name,
                'qty' => $item->qty_per_unit,
                'uom' => $item->unit,
                'dose' => $item->dose ?? '-',
            ])->toArray(),
            'treatment_items' => $this->treatmentIndividuTreatmentItems->map(fn($item) => [
                'name' => $item->name,
                'qty' => 1,
                'uom' => 'Kali',
                'dose' => '-',
            ])->toArray(),
            'notes' => $this->notes,
        ];
    }
}
