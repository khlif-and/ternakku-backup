<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Farm;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Symfony\Component\HttpFoundation\Response;

class CheckFarmOwnershipMiddleware
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

        // Temukan Farm berdasarkan ID
        $farm = Farm::where('owner_id', $user->id)->find($farmId);

        // Jika farm tidak ditemukan, kembalikan respons error
        if (!$farm) {
            return ResponseHelper::error('Farm not found', 404);
        }

        // Simpan farm ke dalam request untuk digunakan di controller
        $request->attributes->set('farm', $farm);

        return $next($request);
    }
}
