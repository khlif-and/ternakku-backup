<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class JwtWebGuard
{
// JwtWebGuard.php
public function handle(Request $request, Closure $next)
{
    $token = $request->cookie('jwt');
    if (!$token) {
        return redirect()->route('login');   // <-- ganti di sini
    }

    try {
        $user = JWTAuth::setToken($token)->authenticate();

        Auth::shouldUse('web');
        Auth::guard('web')->login($user);

    } catch (\Exception $e) {
        Log::error('JWT gagal: '.$e->getMessage());
        return redirect()->route('login');   // <-- dan di sini
    }

    return $next($request);
}



}
