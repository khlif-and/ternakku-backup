<?php

namespace App\Services\Web\Report\TreatmentColony\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TreatmentColonyReportResource extends JsonResource
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
            'livestock_id' => null,
            'livestock_name' => '-',
            'eartag' => '-',
            'pen_name' => $this->pen->name ?? '-',
            'disease_name' => $this->disease->name ?? '-',
            'medicine_items' => $this->treatmentColonyMedicineItems->map(fn($item) => [
                'name' => $item->name,
                'qty' => $item->qty_per_unit,
                'uom' => $item->unit,
                'dose' => $item->dose ?? '-',
            ])->toArray(),
            'treatment_items' => $this->treatmentColonyTreatmentItems->map(fn($item) => [
                'name' => $item->name,
                'qty' => 1,
                'uom' => 'Kali',
                'dose' => '-',
            ])->toArray(),
            'notes' => $this->notes,
        ];
    }
}
