<?php

namespace App\Services\Web\Shared\Driver;

use App\Models\FarmUser;

class DriverService
{
    public function list($farmId, array $filters = [])
    {
        $query = FarmUser::where('farm_id', $farmId)
            ->where('farm_role', 'DRIVER')
            ->with('user');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        return $query->paginate(10);
    }
}
