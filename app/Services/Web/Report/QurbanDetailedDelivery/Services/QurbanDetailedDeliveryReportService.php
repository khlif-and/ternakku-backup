<?php

namespace App\Services\Web\Report\QurbanDetailedDelivery\Services;

use App\Models\QurbanDeliveryInstructionH;
use App\Services\Web\Report\QurbanDetailedDelivery\Resources\QurbanDetailedDeliveryReportResource;

class QurbanDetailedDeliveryReportService
{
    public function getQuery($farmId, array $filters)
    {
        $query = QurbanDeliveryInstructionH::query()
            ->with(['driver', 'fleet', 'qurbanDeliveryInstructionD.qurbanDeliveryOrderH.qurbanCustomerAddress.qurbanCustomer.user'])
            ->where('farm_id', $farmId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['driver_id'])) {
            $query->where('driver_id', $filters['driver_id']);
        }

        if (!empty($filters['fleet_id'])) {
            $query->where('fleet_id', $filters['fleet_id']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('delivery_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('delivery_date', '<=', $filters['end_date']);
        }

        return $query;
    }

    public function generateReport($farmId, array $filters)
    {
        $query = $this->getQuery($farmId, $filters);
        $data = $query->latest()->get();

        return QurbanDetailedDeliveryReportResource::collection($data);
    }
}
