<?php

namespace App\Services\Web\Report\TreatmentIndividu\Services;

use App\Models\TreatmentIndividuD;
use Illuminate\Database\Eloquent\Builder;

class TreatmentIndividuReportService
{
    public function getQuery($farmId, array $filters = []): Builder
    {
        return TreatmentIndividuD::query()
            ->select('treatment_individu_d.*')
            ->join('treatment_h', 'treatment_individu_d.treatment_h_id', '=', 'treatment_h.id')
            ->join('livestocks', 'treatment_individu_d.livestock_id', '=', 'livestocks.id')
            ->where('treatment_h.farm_id', $farmId)
            ->where('treatment_h.type', 'individu')
            ->when(isset($filters['start_date']) && isset($filters['end_date']), function ($q) use ($filters) {
                $q->whereBetween('treatment_h.transaction_date', [$filters['start_date'], $filters['end_date']]);
            })
            ->when(isset($filters['pen_id']) && $filters['pen_id'], function ($q) use ($filters) {
                $q->where('livestocks.pen_id', $filters['pen_id']);
            })
            ->with([
                'treatmentH',
                'livestock.pen',
                'disease',
                'treatmentIndividuMedicineItems',
                'treatmentIndividuTreatmentItems'
            ])
            ->orderBy('treatment_h.transaction_date', 'desc');
    }

    public function getReportData($farmId, array $filters = [])
    {
        return $this->getQuery($farmId, $filters)->paginate(10);
    }

    public function getAll($farmId, array $filters = [])
    {
        return $this->getQuery($farmId, $filters)->get();
    }

    public function find($farmId, $id)
    {
        return $this->getQuery($farmId)
            ->where('treatment_individu_d.id', $id)
            ->firstOrFail();
    }

    public function getSummary($farmId, array $filters = [])
    {
        $query = $this->getQuery($farmId, $filters);

        return [
            'total_treatments' => $query->count(),
        ];
    }
}
