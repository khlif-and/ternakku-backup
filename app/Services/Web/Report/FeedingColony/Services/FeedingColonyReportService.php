<?php

namespace App\Services\Web\Report\FeedingColony\Services;

use App\Models\FeedingColonyD;

class FeedingColonyReportService
{
    private function getQuery($farm, array $filters)
    {
        $query = FeedingColonyD::select('feeding_colony_d.*')
            ->join('feeding_h', 'feeding_colony_d.feeding_h_id', '=', 'feeding_h.id')
            ->where('feeding_h.farm_id', $farm->id)
            ->where('feeding_h.type', 'colony')
            ->with(['feedingH', 'pen', 'feedingColonyItems']);

        if (!empty($filters['start_date'])) {
            $query->whereDate('feeding_h.transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('feeding_h.transaction_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['pen_id'])) {
            $query->where('feeding_colony_d.pen_id', $filters['pen_id']);
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
        $feedTypeSummary = [];

        foreach ($data as $record) {
            $totalCost += $record->total_cost;
            foreach ($record->feedingColonyItems as $item) {
                $totalFeedUsage += $item->qty_kg;

                if (!isset($feedTypeSummary[$item->type])) {
                    $feedTypeSummary[$item->type] = [
                        'type' => $item->type,
                        'total_qty' => 0,
                        'total_cost' => 0,
                    ];
                }
                $feedTypeSummary[$item->type]['total_qty'] += $item->qty_kg;
                $feedTypeSummary[$item->type]['total_cost'] += $item->total_price;
            }
        }

        return [
            'total_cost' => $totalCost,
            'total_kg' => $totalFeedUsage,
            'feed_type_breakdown' => array_values($feedTypeSummary),
        ];
    }

    public function getAll($farm, array $filters)
    {
        return $this->getQuery($farm, $filters)->get();
    }
}
