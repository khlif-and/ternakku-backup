<?php

namespace App\Services\Web\Report\PregnancyCheck\Services;

use App\Models\PregnantCheckD;
use Illuminate\Database\Eloquent\Builder;

class PregnancyCheckReportService
{
    public function getQuery($farmId, array $filters = []): Builder
    {
        $query = PregnantCheckD::query()
            ->with([
                'pregnantCheck',
                'reproductionCycle.livestock.livestockType',
                'reproductionCycle.livestock.livestockBreed',
                'reproductionCycle.livestock.pen',
            ])
            ->whereHas('pregnantCheck', function ($q) use ($farmId) {
                $q->where('farm_id', $farmId);
            });

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereHas('pregnantCheck', function ($q) use ($filters) {
                $q->whereBetween('transaction_date', [$filters['start_date'], $filters['end_date']]);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['livestock_type_id'])) {
            $query->whereHas('reproductionCycle.livestock', function ($q) use ($filters) {
                $q->where('livestock_type_id', $filters['livestock_type_id']);
            });
        }

        if (!empty($filters['livestock_breed_id'])) {
            $query->whereHas('reproductionCycle.livestock', function ($q) use ($filters) {
                $q->where('livestock_breed_id', $filters['livestock_breed_id']);
            });
        }

        if (!empty($filters['pen_id'])) {
            $query->whereHas('reproductionCycle.livestock', function ($q) use ($filters) {
                $q->where('pen_id', $filters['pen_id']);
            });
        }

        if (!empty($filters['livestock_id'])) {
            $query->whereHas('reproductionCycle', function ($q) use ($filters) {
                $q->where('livestock_id', $filters['livestock_id']);
            });
        }

        return $query;
    }
}
