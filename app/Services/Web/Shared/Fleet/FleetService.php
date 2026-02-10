<?php

namespace App\Services\Web\Shared\Fleet;

use App\Models\QurbanFleet;
use Illuminate\Pagination\LengthAwarePaginator;

class FleetService
{
    public function list($farmId, array $filters = []): LengthAwarePaginator
    {
        $query = QurbanFleet::where('farm_id', $farmId)
            ->with('latestPosition');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('police_number', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate(10);
    }
}
