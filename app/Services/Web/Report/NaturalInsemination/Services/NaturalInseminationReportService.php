<?php

namespace App\Services\Web\Report\NaturalInsemination\Services;

use App\Models\InseminationNatural;
use App\Services\Web\Report\NaturalInsemination\Resources\NaturalInseminationReportResource;
use Illuminate\Database\Eloquent\Builder;

class NaturalInseminationReportService
{
    public function getQuery($farmId, array $filters)
    {
        // Using 'Natural' with capital N as seen in NaturalInseminationController
        $query = InseminationNatural::query()
            ->with(['insemination', 'insemination.farm', 'reproductionCycle.livestock', 'sireBreed'])
            ->whereHas('insemination', function ($q) use ($farmId, $filters) {
                $q->where('farm_id', $farmId)
                    ->where('type', 'natural'); // Check if migration uses lowercase or capitalized
    
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

        return NaturalInseminationReportResource::collection($data);
    }
}
