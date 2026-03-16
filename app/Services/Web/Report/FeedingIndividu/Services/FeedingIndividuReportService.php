<?php

namespace App\Services\Web\Report\FeedingIndividu\Services;

use App\Models\FeedingIndividuD;

class FeedingIndividuReportService
{
    private function getQuery($farm, array $filters)
    {
        $query = FeedingIndividuD::select('feeding_individu_d.*')
            ->join('feeding_h', 'feeding_individu_d.feeding_h_id', '=', 'feeding_h.id')
            ->join('livestocks', 'feeding_individu_d.livestock_id', '=', 'livestocks.id')
            ->where('feeding_h.farm_id', $farm->id)
            ->where('feeding_h.type', 'individu')
            ->with(['feedingH', 'livestock.pen', 'feedingIndividuItems']);

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('feeding_h.transaction_date', [$filters['start_date'], $filters['end_date']]);
        }

        if (!empty($filters['pen_id'])) {
            $query->where('livestocks.pen_id', $filters['pen_id']);
        }

        if (!empty($filters['livestock_id'])) {
            $query->where('feeding_individu_d.livestock_id', $filters['livestock_id']);
        }

        $query->orderBy('feeding_h.transaction_date', 'desc');

        return $query;
    }

    public function getReportData($farm, array $filters)
    {
        return $this->getQuery($farm, $filters)->paginate(10);
    }

    public function getSummary($farm, array $filters)
    {
        $data = $this->getQuery($farm, $filters)->get();

        $totalCost = 0;
        $totalFeedUsage = 0;

        foreach ($data as $record) {
            foreach ($record->feedingIndividuItems as $item) {
                $totalFeedUsage += $item->qty_kg;
                $totalCost += $item->total_price;
            }
        }

        return [
            'total_cost' => $totalCost,
            'total_kg' => $totalFeedUsage,
        ];
    }

    public function getAll($farm, array $filters)
    {
        return $this->getQuery($farm, $filters)->get();
    }
}
