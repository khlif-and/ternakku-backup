<?php

namespace App\Services\Web\Report\MilkProductionIndividu\Services;

use App\Models\MilkProductionIndividuD;
use Illuminate\Database\Eloquent\Builder;

class MilkProductionIndividuReportService
{
    public function getQuery($farmId, array $filters = []): Builder
    {
        return MilkProductionIndividuD::query()
            ->with(['milkProductionH', 'livestock'])
            ->whereHas('milkProductionH', function ($query) use ($farmId, $filters) {
                $query->where('farm_id', $farmId);

                if (isset($filters['start_date']) && isset($filters['end_date'])) {
                    $query->whereBetween('transaction_date', [$filters['start_date'], $filters['end_date']]);
                }
            })
            ->when(isset($filters['livestock_id']) && $filters['livestock_id'], function ($query) use ($filters) {
                $query->where('livestock_id', $filters['livestock_id']);
            })
            ->latest('id');
    }

    public function getReportData($farmId, array $filters = [])
    {
        return $this->getQuery($farmId, $filters)
            ->paginate(10)
            ->withQueryString();
    }

    public function getAll($farmId, array $filters = [])
    {
        return $this->getQuery($farmId, $filters)->get();
    }

    public function find($farmId, $id)
    {
        return $this->getQuery($farmId)
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getSummary($farmId, array $filters = [])
    {
        $query = $this->getQuery($farmId, $filters);

        return [
            'total_production' => $query->sum('quantity_liters'),
            'avg_production' => $query->avg('quantity_liters') ?? 0,
            'total_records' => $query->count(),
        ];
    }
}
