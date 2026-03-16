<?php

namespace App\Services\Web\Report\SalesOrder\Services;

use App\Models\QurbanSalesOrderD;
use Illuminate\Database\Eloquent\Builder;

class SalesOrderReportService
{
    public function getQuery($farmId, array $filters = []): Builder
    {
        $query = QurbanSalesOrderD::query()
            ->with([
                'qurbanSalesOrder.qurbanCustomer.user',
                'livestockType',
            ])
            ->whereHas('qurbanSalesOrder', function ($q) use ($farmId) {
                $q->where('farm_id', $farmId);
            });

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereHas('qurbanSalesOrder', function ($q) use ($filters) {
                $q->whereBetween('order_date', [$filters['start_date'], $filters['end_date']]);
            });
        }

        if (!empty($filters['qurban_customer_id'])) {
            $query->whereHas('qurbanSalesOrder', function ($q) use ($filters) {
                $q->where('qurban_customer_id', $filters['qurban_customer_id']);
            });
        }

        if (!empty($filters['livestock_type_id'])) {
            $query->where('livestock_type_id', $filters['livestock_type_id']);
        }

        return $query;
    }
}
