<?php

namespace App\Services\Web\Report\MilkProductionGlobal\Services;

use App\Models\MilkProductionGlobal;
use Illuminate\Database\Eloquent\Builder;

class MilkProductionGlobalReportService
{
    public function getQuery($farmId, array $filters = []): Builder
    {
        return MilkProductionGlobal::query()
            ->where('farm_id', $farmId)
            ->when(isset($filters['start_date']) && isset($filters['end_date']), function ($q) use ($filters) {
                $q->whereBetween('transaction_date', [$filters['start_date'], $filters['end_date']]);
            })
            ->orderBy('transaction_date', 'desc');
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
        $data = $this->getQuery($farmId, $filters)
            ->toBase()
            ->selectRaw('count(*) as total_records, sum(quantity_liters) as total_production, avg(quantity_liters) as avg_production')
            ->first();

        return [
            'total_production' => $data->total_production ?? 0,
            'avg_production' => $data->avg_production ?? 0,
            'total_records' => $data->total_records ?? 0,
        ];
    }
}
