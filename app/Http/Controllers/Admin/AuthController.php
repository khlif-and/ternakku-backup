<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    public function login(LoginRequest $request)
    {
        $response = $this->authService->login($request);

        if ($response['error']) {
            // Redirect back with an error message
            return redirect()->back()->withErrors([
                'login_error' => $response['message']
            ])->withInput(); // Keep the old input for convenience
        }

        // Ambil user dari respons
        $user = $response['data']['user'];

        // Login user untuk sesi web
        Auth::login($user);

        // Redirect ke dashboard atau halaman lain dengan pesan sukses
        return redirect('dashboard')->with('success', $response['message']);
    }

    public function logout(Request $request)
    {
        // Logout the user
        Auth::logout();

        // Invalidate the session to prevent reuse
        $request->session()->invalidate();

        // Regenerate CSRF token for security
        $request->session()->regenerateToken();

        // Redirect to the login page (or any other page)
        return redirect()->route('auth/login')->with('success', 'You have been logged out successfully.');
    }

}
