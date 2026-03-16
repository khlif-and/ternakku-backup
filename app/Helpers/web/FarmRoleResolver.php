<?php

namespace App\Helpers\web;

use App\Models\Farm;
use App\Models\FarmUser;

class FarmRoleResolver
{
    public static function resolve(?int $farmId = null): array
    {
        $farmId = $farmId ?? session('selected_farm');
        $userId = auth()->id();

        $userRole = FarmUser::where('user_id', $userId)
            ->where('farm_id', $farmId)
            ->value('farm_role') ?? '';

        $farmData = Farm::find($farmId);
        $isOwner = $userRole === 'OWNER' || ($farmData && $farmData->owner_id === $userId);

        return [
            'currentFarm' => $farmData,
            'userRole' => $userRole,
            'isOwner' => $isOwner,
            'isAdmin' => $userRole === 'ADMIN',
            'isMarketing' => $userRole === 'MARKETING',
            'isDriver' => $userRole === 'DRIVER',
        ];
    }
}