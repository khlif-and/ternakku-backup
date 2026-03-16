<?php

namespace App\Services\Web\Report\Birth\Services;

use App\Models\LivestockBirthD;
use Illuminate\Database\Eloquent\Builder;

class BirthReportService
{
    public function getQuery($farmId, array $filters = []): Builder
    {
        $query = LivestockBirthD::query()
            ->with([
                'livestockBirth.reproductionCycle.livestock.livestockType',
                'livestockBirth.reproductionCycle.livestock.livestockBreed',
                'livestockBirth.reproductionCycle.livestock.pen',
                'livestockSex',
                'livestockBreed',
                'disease',
            ])
            ->whereHas('livestockBirth', function ($q) use ($farmId) {
                $q->where('farm_id', $farmId);
            });

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereHas('livestockBirth', function ($q) use ($filters) {
                $q->whereBetween('transaction_date', [$filters['start_date'], $filters['end_date']]);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['livestock_type_id'])) {
            $query->whereHas('livestockBirth.reproductionCycle.livestock', function ($q) use ($filters) {
                $q->where('livestock_type_id', $filters['livestock_type_id']);
            });
        }

        if (!empty($filters['livestock_breed_id'])) {
            $query->whereHas('livestockBirth.reproductionCycle.livestock', function ($q) use ($filters) {
                $q->where('livestock_breed_id', $filters['livestock_breed_id']);
            });
        }

        if (!empty($filters['pen_id'])) {
            $query->whereHas('livestockBirth.reproductionCycle.livestock', function ($q) use ($filters) {
                $q->where('pen_id', $filters['pen_id']);
            });
        }

        if (!empty($filters['livestock_id'])) {
            $query->whereHas('livestockBirth.reproductionCycle', function ($q) use ($filters) {
                $q->where('livestock_id', $filters['livestock_id']);
            });
        }

        return $query;
    }
}
