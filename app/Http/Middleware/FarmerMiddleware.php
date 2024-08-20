<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Symfony\Component\HttpFoundation\Response;

class FarmerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->isFarmer()){
            return $next($request);
        }
        return ResponseHelper::error('Access denied: The user does not hold the farmer role.', 401);
    }
}
