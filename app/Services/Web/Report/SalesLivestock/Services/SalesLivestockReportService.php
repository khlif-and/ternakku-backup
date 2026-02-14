<?php

namespace App\Services\Web\Report\SalesLivestock\Services;

use App\Models\QurbanSaleLivestockH;
use App\Services\Web\Report\SalesLivestock\Resources\SalesLivestockReportResource;
use Illuminate\Support\Facades\DB;

class SalesLivestockReportService
{
    public function getQuery($farmId, array $filters)
    {
        $query = QurbanSaleLivestockH::query()
            ->with(['qurbanCustomer.user', 'qurbanSaleLivestockD.livestock'])
            ->where('farm_id', $farmId)
            ->whereBetween('transaction_date', [$filters['start_date'], $filters['end_date']]);

        if (!empty($filters['qurban_customer_id'])) {
            $query->where('qurban_customer_id', $filters['qurban_customer_id']);
        }

        return $query;
    }

    public function generateReport($farmId, array $filters)
    {
        $query = $this->getQuery($farmId, $filters);
        $data = $query->latest()->get();

        return SalesLivestockReportResource::collection($data);
    }

    public function getSummary($farmId, array $filters)
    {
        $query = $this->getQuery($farmId, $filters);

        // Calculate totals using collection logic since details are nested
        $sales = $query->get();

        $totalRevenue = 0;
        $totalLivestockSold = 0;

        foreach ($sales as $sale) {
            foreach ($sale->qurbanSaleLivestockD as $detail) {
                $totalRevenue += ($detail->price_per_head ?? 0) + (($detail->price_per_kg ?? 0) * ($detail->weight ?? 0));
                $totalLivestockSold++;
            }
        }

        return [
            'total_revenue' => $totalRevenue,
            'total_livestock_sold' => $totalLivestockSold,
        ];
    }
}
