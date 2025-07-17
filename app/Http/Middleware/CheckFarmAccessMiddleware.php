<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\FarmUser;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Symfony\Component\HttpFoundation\Response;

class CheckFarmAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();
        $farmId = $request->route('farm_id');

        $farmUsers = FarmUser::where('user_id', $user->id)
            ->where('farm_id', $farmId)
            ->get();

        if ($farmUsers->isEmpty()) {
            if ($request->expectsJson()) {
                return ResponseHelper::error('You do not have access to this farm or the farm does not exist', 404);
            }
            abort(403, 'You do not have access to this farm or the farm does not exist.');
        }

        if (!empty($roles) && !$farmUsers->contains(fn($record) => in_array($record->farm_role, $roles))) {
            if ($request->expectsJson()) {
                return ResponseHelper::error('You do not have permission to perform this action', 403);
            }
            abort(403, 'You do not have permission to perform this action.');
        }

        // Simpan farm instance ke request
        $request->attributes->set('farm', $farmUsers->first()->farm);

        return $next($request);
    }
}
