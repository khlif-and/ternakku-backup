<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEmailVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the email is verified using the method in the User model
            if (!$user->hasVerifiedEmail()) {
                if ($request->expectsJson()) {
                    // Return JSON response for API requests
                    return response()->json(['error' => 'Email not verified.'], 403);
                } else {
                    // Return view for web requests
                    return response()->view('auth.verify-email');
                }
            }
        }

        return $next($request);
    }

}
