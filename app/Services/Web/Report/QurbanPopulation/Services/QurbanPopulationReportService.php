<?php

namespace App\Services\Web\Report\QurbanPopulation\Services;

use App\Models\Livestock;
use Illuminate\Database\Eloquent\Builder;

class QurbanPopulationReportService
{
    public function getQuery($farmId, array $filters = []): Builder
    {
        $query = Livestock::query()
            ->with([
                'livestockType',
                'livestockBreed',
                'livestockReceptionD',
                'qurbanLivestock',
                'livestockStatus',
                'livestockSex',
            ])
            ->where('farm_id', $farmId)
            ->whereHas('qurbanLivestock');

        if (!empty($filters['livestock_type_id'])) {
            $query->where('livestock_type_id', $filters['livestock_type_id']);
        }

        if (!empty($filters['livestock_breed_id'])) {
            $query->where('livestock_breed_id', $filters['livestock_breed_id']);
        }

        if (!empty($filters['livestock_status_id'])) {
            $query->where('livestock_status_id', $filters['livestock_status_id']);
        }

        return $query;
    }
}
