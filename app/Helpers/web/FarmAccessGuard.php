<?php

namespace App\Helpers\web;

use App\Models\Farm;
use App\Models\FarmUser;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class FarmAccessGuard
{
    public static function validate(?int $farmId = null): Farm
    {
        $farmId = $farmId ?? session('selected_farm');

        if (!$farmId) {
            abort(403, 'Tidak ada farm yang dipilih.');
        }

        $farm = Farm::find($farmId);

        if (!$farm) {
            abort(404, 'Farm tidak ditemukan.');
        }

        $userId = auth()->id();

        $isMember = FarmUser::where('user_id', $userId)
            ->where('farm_id', $farmId)
            ->exists();

        if (!$isMember && $farm->owner_id !== $userId) {
            abort(403, 'Anda tidak memiliki akses ke farm ini.');
        }

        return $farm;
    }

    public static function validateRole(?int $farmId, string ...$roles): Farm
    {
        $farm = self::validate($farmId);

        $userId = auth()->id();

        if ($farm->owner_id === $userId) {
            return $farm;
        }

        $userRole = FarmUser::where('user_id', $userId)
            ->where('farm_id', $farm->id)
            ->value('farm_role');

        if (!in_array($userRole, $roles, true)) {
            abort(403, 'Anda tidak memiliki peran yang diperlukan.');
        }

        return $farm;
    }

    public static function scopedFind(string $modelClass, int $id, int $farmId)
    {
        return $modelClass::where('farm_id', $farmId)->findOrFail($id);
    }

    public static function sanitizeRedirectUrl(?string $url): string
    {
        if (!$url || !str_starts_with($url, '/') || str_starts_with($url, '//')) {
            return '/dashboard';
        }

        return $url;
    }
}
