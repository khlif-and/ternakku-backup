<?php

namespace App\Services\Web\Report\QurbanPayment\Services;

use App\Models\QurbanSaleLivestockPayment;
use App\Services\Web\Report\QurbanPayment\Resources\QurbanPaymentReportResource;
use Illuminate\Support\Facades\DB;

class QurbanPaymentReportService
{
    public function getQuery($farmId, array $filters)
    {
        $query = QurbanSaleLivestockPayment::query()
            ->with(['qurbanCustomer.user', 'qurbanSaleLivestockH'])
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

        return QurbanPaymentReportResource::collection($data);
    }

    public function getSummary($farmId, array $filters)
    {
        $query = $this->getQuery($farmId, $filters);

        $totalAmount = $query->sum('amount');
        $totalTransactions = $query->count();

        return [
            'total_amount' => $totalAmount,
            'total_transactions' => $totalTransactions,
        ];
    }
}
