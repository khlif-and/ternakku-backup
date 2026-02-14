<?php

namespace App\Services\Web\Report\ArtificialInsemination\Services;

use App\Models\InseminationArtificial;
use App\Services\Web\Report\ArtificialInsemination\Resources\ArtificialInseminationReportResource;
use Illuminate\Database\Eloquent\Builder;

class ArtificialInseminationReportService
{
    public function getQuery($farmId, array $filters)
    {
        $query = InseminationArtificial::query()
            ->with(['insemination', 'insemination.farm', 'reproductionCycle.livestock', 'semenBreed'])
            ->whereHas('insemination', function ($q) use ($farmId, $filters) {
                $q->where('farm_id', $farmId)
                    ->where('type', 'artificial');

                if (!empty($filters['start_date'])) {
                    $q->where('transaction_date', '>=', $filters['start_date']);
                }

                if (!empty($filters['end_date'])) {
                    $q->where('transaction_date', '<=', $filters['end_date']);
                }
            });

        // Filters on Livestock
        $query->whereHas('reproductionCycle.livestock', function ($q) use ($filters) {
            if (!empty($filters['livestock_id'])) {
                $q->where('id', $filters['livestock_id']);
            }
            if (!empty($filters['livestock_type_id'])) {
                $q->where('livestock_type_id', $filters['livestock_type_id']);
            }
            if (!empty($filters['livestock_group_id'])) {
                $q->where('livestock_group_id', $filters['livestock_group_id']);
            }
            if (!empty($filters['livestock_breed_id'])) {
                $q->where('livestock_breed_id', $filters['livestock_breed_id']);
            }
            if (!empty($filters['pen_id'])) {
                $q->where('pen_id', $filters['pen_id']);
            }
        });

        return $query;
    }

    public function generateReport($farmId, array $filters)
    {
        $query = $this->getQuery($farmId, $filters);
        $data = $query->get();

        return ArtificialInseminationReportResource::collection($data);
    }
}
