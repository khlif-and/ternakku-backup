<?php

namespace App\Services\Web\Report\MilkAnalysisIndividu\Services;

use App\Models\MilkAnalysisIndividuD;
use App\Services\Web\Report\MilkAnalysisIndividu\Resources\MilkAnalysisIndividuReportResource;
use Illuminate\Support\Facades\DB;

class MilkAnalysisIndividuReportService
{
    public function getQuery($farmId, array $filters)
    {
        $query = MilkAnalysisIndividuD::query()
            ->with(['milkAnalysisH', 'livestock'])
            ->whereHas('milkAnalysisH', function ($q) use ($farmId, $filters) {
                $q->where('farm_id', $farmId)
                    ->where('type', 'individu')
                    ->whereBetween('transaction_date', [$filters['start_date'], $filters['end_date']]);
            });

        if (!empty($filters['livestock_id'])) {
            $query->where('livestock_id', $filters['livestock_id']);
        }

        return $query;
    }

    public function generateReport($farmId, array $filters)
    {
        $query = $this->getQuery($farmId, $filters);
        $data = $query->get();

        return MilkAnalysisIndividuReportResource::collection($data);
    }

    public function getSummary($farmId, array $filters)
    {
        $query = $this->getQuery($farmId, $filters);

        $stats = $query->select(
            DB::raw('AVG(fat) as avg_fat'),
            DB::raw('AVG(snf) as avg_snf'),
            DB::raw('AVG(protein) as avg_protein'),
            DB::raw('AVG(bj) as avg_bj')
        )->first();

        return [
            'avg_fat' => $stats->avg_fat ?? 0,
            'avg_snf' => $stats->avg_snf ?? 0,
            'avg_protein' => $stats->avg_protein ?? 0,
            'avg_bj' => $stats->avg_bj ?? 0,
        ];
    }
}
