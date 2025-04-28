<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Models\LivestockStatus;
use App\Models\SubscriptionFarm;
use App\Enums\LivestockStatusEnum;
use Symfony\Component\HttpFoundation\Response;

class BasicFarmingSubsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $farmId = $request->route('farm_id');

        $check = SubscriptionFarm::where('farm_id', $farmId)
            ->where('subscription_id', 1)
            ->whereNotNull('confirmation_date')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$check) {
            return response()->json([
                'message' => 'You do not have an active subscription for this farm or the farm does not exist',
            ], 404);
        }

        $livestockCount = Livestock::where('farm_id', $farmId)
            ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
            ->count();

        if ($livestockCount > $check->quantity * 50 * 1.1) {
            return response()->json([
                'message' => 'You have reached the maximum number of livestock for this farm',
            ], 400);
        }
        return $next($request);
    }
}
