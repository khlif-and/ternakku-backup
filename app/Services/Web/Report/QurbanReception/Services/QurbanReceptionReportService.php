<?php

namespace App\Services\Web\Report\QurbanReception\Services;

use App\Models\LivestockReceptionH;

class QurbanReceptionReportService
{
    public function getQuery($farmId, array $filters)
    {
        $query = LivestockReceptionH::query()
            ->with([
                'livestockReceptionD' => function ($q) {
                    $q->whereHas('livestock.qurbanLivestock');
                    $q->with([
                        'livestockType',
                        'livestockBreed',
                        'livestockSex',
                        'pen',
                        'livestock.qurbanLivestock',
                    ]);
                },
            ])
            ->where('farm_id', $farmId)
            ->whereHas('livestockReceptionD.livestock.qurbanLivestock');

        if (!empty($filters['start_date'])) {
            $query->whereDate('transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('transaction_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['supplier'])) {
            $query->where('supplier', 'like', '%' . $filters['supplier'] . '%');
        }

        if (!empty($filters['livestock_type_id'])) {
            $query->whereHas('livestockReceptionD', function ($q) use ($filters) {
                $q->where('livestock_type_id', $filters['livestock_type_id']);
            });
        }

        return $query;
    }

    public function generateReport($farmId, array $filters)
    {
        $query = $this->getQuery($farmId, $filters);
        return $query->latest('transaction_date')->get();
    }
}
