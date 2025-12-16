<?php

namespace App\Services\Web\Report;

use App\Models\{MutationH, MutationIndividuD};
use Illuminate\Support\Facades\DB;

class Mutation_Individu_Report_Core_Service
{
    /**
     * Ambil list mutasi individu berdasarkan filter report
     */
    public function listReport($farm, array $filters): array
    {
        $query = MutationIndividuD::with(['mutationH', 'livestock'])
            ->whereHas('mutationH', function ($q) use ($farm, $filters) {
                $q->where('farm_id', $farm->id)
                  ->where('type', 'individu');

                if (!empty($filters['start_date'])) {
                    $q->where('transaction_date', '>=', $filters['start_date']);
                }
                if (!empty($filters['end_date'])) {
                    $q->where('transaction_date', '<=', $filters['end_date']);
                }
            });

        // Filter by pen
        if (!empty($filters['pen_id'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('from', $filters['pen_id'])
                  ->orWhere('to', $filters['pen_id']);
            });
        }

        return $query->orderBy('id', 'DESC')->get()->all();
    }

    /**
     * Ambil satu data mutasi individu untuk kebutuhan report detail
     */
    public function findOneReport($farm, $mutationId)
    {
        return MutationIndividuD::with(['mutationH', 'livestock'])
            ->whereHas('mutationH', function ($q) use ($farm) {
                $q->where('farm_id', $farm->id)
                  ->where('type', 'individu');
            })
            ->findOrFail($mutationId);
    }
}
