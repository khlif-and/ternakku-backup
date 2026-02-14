<?php

namespace App\Services\Web\Report\MutationIndividu\Services;

use App\Models\MutationIndividuD;
use App\Services\Web\Report\MutationIndividu\Resources\MutationIndividuReportResource;

class MutationIndividuReportService
{
    public function getQuery($farmId, array $filters)
    {
        $query = MutationIndividuD::query()
            ->with(['mutationH', 'livestock', 'penFrom', 'penTo'])
            ->whereHas('mutationH', function ($q) use ($farmId, $filters) {
                $q->where('farm_id', $farmId)
                    ->where('type', 'individu');

                if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                    $q->whereBetween('transaction_date', [$filters['start_date'], $filters['end_date']]);
                }
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

        return MutationIndividuReportResource::collection($data);
    }

    public function getById($farmId, $id)
    {
        $item = MutationIndividuD::with(['mutationH', 'livestock', 'penFrom', 'penTo'])
            ->whereHas('mutationH', function ($q) use ($farmId) {
                $q->where('farm_id', $farmId)->where('type', 'individu');
            })
            ->find($id);

        return $item ? new MutationIndividuReportResource($item) : null;
    }
}
