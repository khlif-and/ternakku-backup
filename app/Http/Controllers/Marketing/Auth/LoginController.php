<?php

namespace App\Http\Controllers\Marketing\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FarmUser;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('marketing.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            $isMarketing = FarmUser::where('user_id', $user->id)
                ->where('farm_role', 'MARKETING')
                ->exists();

            if (!$isMarketing) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak terdaftar sebagai marketing.',
                ])->withInput($request->only('email'));
            }

            $request->session()->regenerate();

            return redirect()->intended(route('marketing.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('marketing.login');
    }
}
