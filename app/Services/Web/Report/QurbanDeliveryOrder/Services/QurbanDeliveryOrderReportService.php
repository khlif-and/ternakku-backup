<?php

namespace App\Services\Web\Report\QurbanDeliveryOrder\Services;

use App\Models\QurbanDeliveryOrderH;
use Illuminate\Http\Request;

class QurbanDeliveryOrderReportService
{
    public function getReportData(Request $request, $farmId)
    {
        $query = QurbanDeliveryOrderH::query()
            ->with(['qurbanCustomerAddress.qurbanCustomer', 'qurbanSaleLivestockH'])
            ->where('farm_id', $farmId);

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        if ($request->has('qurban_customer_id') && $request->qurban_customer_id) {
            $query->whereHas('qurbanCustomerAddress', function ($q) use ($request) {
                $q->where('qurban_customer_id', $request->qurban_customer_id);
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $query->orderBy('transaction_date', 'desc');

        if ($request->has('export') && $request->export) {
            return $query->get();
        }

        return $query->paginate($request->per_page ?? 10);
    }
}
