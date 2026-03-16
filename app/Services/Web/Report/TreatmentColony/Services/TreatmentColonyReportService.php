<?php

namespace App\Services\Web\Report\TreatmentColony\Services;

use App\Models\TreatmentColonyD;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TreatmentColonyReportService
{
    /**
     * Get the base query for the report
     */
    public function getQuery($farmId, array $filters = []): Builder
    {
        return TreatmentColonyD::query()
            ->select('treatment_colony_d.*')
            ->join('treatment_h', 'treatment_colony_d.treatment_h_id', '=', 'treatment_h.id')
            ->where('treatment_h.farm_id', $farmId)
            ->where('treatment_h.type', 'colony')
            ->when(isset($filters['start_date']) && isset($filters['end_date']), function ($q) use ($filters) {
                $q->whereBetween('treatment_h.transaction_date', [$filters['start_date'], $filters['end_date']]);
            })
            ->when(isset($filters['pen_id']) && $filters['pen_id'], function ($q) use ($filters) {
                $q->where('treatment_colony_d.pen_id', $filters['pen_id']);
            })
            ->with([
                'treatmentH',
                'pen',
                'disease',
                'treatmentColonyMedicineItems',
                'treatmentColonyTreatmentItems'
            ])
            ->orderBy('treatment_h.transaction_date', 'desc');
    }

    /**
     * Get paginated report data
     */
    public function getReportData($farmId, array $filters = [], int $perPage = 10)
    {
        return $this->getQuery($farmId, $filters)->paginate($perPage);
    }

    /**
     * Get summary statistics
     */
    public function getSummary($farmId, array $filters = [])
    {
        $query = $this->getQuery($farmId, $filters);

        // We need a fresh query for count to avoid issues with select/joins if not needed, 
        // but getQuery already has joins which differ from simple count.
        // However, for total records count, we can just count the results of getQuery.
        // But for specific sums (like total cost if available), we might need logic.
        // Based on original Index.php, it just counts.

        return [
            'total_treatments' => $query->count(),
        ];
    }

    /**
     * Get all data for export
     */
    public function getAll($farmId, array $filters = [])
    {
        return $this->getQuery($farmId, $filters)->get();
    }

    /**
     * Get single record for export
     */
    public function find($farmId, $id)
    {
        return $this->getQuery($farmId)->where('treatment_colony_d.id', $id)->firstOrFail();
    }
}
