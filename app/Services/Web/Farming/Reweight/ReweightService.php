<?php

namespace App\Services\Web\Farming\Reweight;

use App\Models\LivestockReweightD;

class ReweightService
{
    public function list($farmId, $filters = [])
    {
        $query = LivestockReweightD::with(['livestock', 'livestockReweightH'])
            ->whereHas('livestockReweightH', function ($q) use ($farmId) {
                $q->where('farm_id', $farmId);
            });

        // Date Filter
        if (!empty($filters['start_date'])) {
            $query->whereHas('livestockReweightH', function($q) use ($filters) {
                $q->where('transaction_date', '>=', $filters['start_date']);
            });
        }
        if (!empty($filters['end_date'])) {
            $query->whereHas('livestockReweightH', function($q) use ($filters) {
                $q->where('transaction_date', '<=', $filters['end_date']);
            });
        }

        // Livestock Filters
        if (!empty($filters['livestock_type_id'])) {
            $query->whereHas('livestock', function ($q) use ($filters) {
                $q->where('livestock_type_id', $filters['livestock_type_id']);
            });
        }
        
        if (!empty($filters['livestock_id'])) {
            $query->where('livestock_id', $filters['livestock_id']);
        }
        
        if (!empty($filters['search'])) {
            $query->whereHas('livestock', function ($q) use ($filters) {
                $q->where('eartag', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->latest()->paginate($filters['per_page'] ?? 10);
    }
}
