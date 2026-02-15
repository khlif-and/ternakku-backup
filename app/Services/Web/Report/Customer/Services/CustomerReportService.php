<?php

namespace App\Services\Web\Report\Customer\Services;

use App\Models\QurbanCustomer;
use Illuminate\Database\Eloquent\Builder;

class CustomerReportService
{
    public function getQuery($farmId, array $filters = []): Builder
    {
        $query = QurbanCustomer::query()
            ->with([
                'user',
                'addresses.region',
            ])
            ->where('farm_id', $farmId);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
