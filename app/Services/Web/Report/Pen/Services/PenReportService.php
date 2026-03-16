<?php

namespace App\Services\Web\Report\Pen\Services;

use App\Models\Pen;
use App\Services\Web\Report\Pen\Resources\PenReportResource;

class PenReportService
{
    public function getQuery($farmId, array $filters)
    {
        $query = Pen::query()
            ->withCount([
                'livestocks' => function ($query) {
                    $query->where('livestock_status_id', 1);
                }
            ])
            ->where('farm_id', $farmId);

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query;
    }

    public function generateReport($farmId, array $filters)
    {
        $query = $this->getQuery($farmId, $filters);
        $data = $query->get();

        return PenReportResource::collection($data);
    }

    public function getSummary($farmId, array $filters)
    {
        $query = $this->getQuery($farmId, $filters);
        $pens = $query->get();

        $totalCapacity = $pens->sum('capacity');
        $totalPopulation = $pens->sum('livestocks_count');

        $occupancyRate = $totalCapacity > 0 ? ($totalPopulation / $totalCapacity) * 100 : 0;

        return [
            'total_pens' => $pens->count(),
            'total_capacity' => $totalCapacity,
            'total_population' => $totalPopulation,
            'occupancy_rate' => round($occupancyRate, 2),
        ];
    }
}
