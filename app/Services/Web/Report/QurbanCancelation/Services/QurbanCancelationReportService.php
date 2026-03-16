<?php

namespace App\Services\Web\Report\QurbanCancelation\Services;

use App\Models\QurbanSalesOrder;
use App\Services\Web\Report\QurbanCancelation\Resources\QurbanCancelationReportResource;

class QurbanCancelationReportService
{
    public function getQuery($farmId, array $filters)
    {
        $query = QurbanSalesOrder::query()
            ->with(['qurbanCustomer.user'])
            ->where('farm_id', $farmId)
            ->where('status', 'cancelled')
            ->whereBetween('order_date', [$filters['start_date'], $filters['end_date']]);

        if (!empty($filters['qurban_customer_id'])) {
            $query->where('qurban_customer_id', $filters['qurban_customer_id']);
        }

        return $query;
    }

    public function generateReport($farmId, array $filters)
    {
        $query = $this->getQuery($farmId, $filters);
        $data = $query->latest()->get();

        return QurbanCancelationReportResource::collection($data);
    }

    public function getSummary($farmId, array $filters)
    {
        $query = $this->getQuery($farmId, $filters);

        $totalTransactions = $query->count();

        return [
            'total_transactions' => $totalTransactions,
        ];
    }
}
