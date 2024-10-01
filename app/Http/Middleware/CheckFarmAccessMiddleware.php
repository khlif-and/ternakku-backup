<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Farm;
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
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $farmId = $request->route('farm_id');

        $check = FarmUser::where('user_id', $user->id)->where('farm_id' , $farmId)->first();

        if (!$check) {
            return ResponseHelper::error('You do not have access to this farm or the farm does not exist', 404);
        }

        // Simpan farm ke dalam request untuk digunakan di controller
        $farm = $check->farm;
        $request->attributes->set('farm', $farm);

        return $next($request);
    }
}
