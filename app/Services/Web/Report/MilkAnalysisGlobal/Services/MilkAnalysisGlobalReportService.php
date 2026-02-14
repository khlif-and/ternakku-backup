<?php

namespace App\Services\Web\Report\MilkAnalysisGlobal\Services;

use App\Models\Farm;
use App\Models\MilkAnalysisGlobal;
use App\Services\Web\Report\MilkAnalysisGlobal\Resources\MilkAnalysisGlobalReportResource;

class MilkAnalysisGlobalReportService
{
    public function generateReport(Farm $farm, array $filters = [])
    {
        $query = $this->getQuery($farm, $filters);

        return MilkAnalysisGlobalReportResource::collection($query->get());
    }

    public function getQuery(Farm $farm, array $filters = [])
    {
        $query = MilkAnalysisGlobal::where('farm_id', $farm->id);

        if (!empty($filters['start_date'])) {
            $query->whereDate('transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('transaction_date', '<=', $filters['end_date']);
        }

        return $query->latest('transaction_date');
    }

    public function getSummary(Farm $farm, array $filters = [])
    {
        $query = $this->getQuery($farm, $filters);

        return [
            'avg_bj' => $query->avg('bj'),
            'avg_fat' => $query->avg('fat'),
            'avg_protein' => $query->avg('protein'),
            'avg_snf' => $query->avg('snf'),
            'total_records' => $query->count(),
        ];
    }
}
