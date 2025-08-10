<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\User;
use App\Models\Otp;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function showRegisterForm(Request $request)
    {
        $pendingEmail = session('pending_verify_email');

        if ($pendingEmail) {
            $user = User::where('email', $pendingEmail)->first();

            if ($user && is_null($user->email_verified_at)) {
                return $this->redirectToVerify($request, $user);
            }

            session()->forget('pending_verify_email');
        }

        return view('admin.auth.register');
    }

    public function login(LoginRequest $request)
    {
        $response = $this->authService->login($request);

        if ($response['error']) {
            return back()
                ->withErrors(['login_error' => $response['message']])
                ->withInput();
        }

        /** @var \App\Models\User $user */
        $user = $response['data']['user'];

        if (is_null($user->email_verified_at)) {
            session(['pending_verify_email' => $user->email]);
            return $this->redirectToVerify($request, $user);
        }

        Auth::login($user);
        return redirect()->route('dashboard')->with('success', $response['message']);
    }

    public function register(RegisterRequest $request)
    {
        try {
            $existingUser = User::where('email', $request->email)->first();

            if ($existingUser && is_null($existingUser->email_verified_at)) {
                return $this->redirectToVerify($request, $existingUser);
            }

            $user = $this->authService->register($request->validated());

            $user->roles()->syncWithoutDetaching([
                \App\Enums\RoleEnum::FARMER->value => [
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            session(['pending_verify_email' => $user->email]);

            return $this->redirectToVerify($request, $user);
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['register_error' => 'Registrasi gagal: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        $otp = Otp::where('user_id', $user->id)
            ->where('code', $request->otp)
            ->where('is_used', false)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->orderByDesc('created_at')
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah digunakan.'])->withInput();
        }

        $otp->update(['is_used' => true]);

        $user->email_verified_at = now();
        $user->save();

        Auth::login($user);
        session()->forget('pending_verify_email');

        return redirect()->route('dashboard')->with('success', 'Verifikasi berhasil. Selamat datang!');
    }

    public function resendOtp(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User tidak ditemukan.']);
        }

        // generate OTP unik
        do {
            $newOtp = random_int(100000, 999999);
        } while (
            Otp::where('user_id', $user->id)
                ->where('code', $newOtp)
                ->where('is_used', false)
                ->exists()
        );

        // invalidate OTP lama yang belum dipakai
        Otp::where('user_id', $user->id)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        Otp::create([
            'user_id' => $user->id,
            'code'    => $newOtp,
            'is_used' => false,
        ]);

        return back()->with('success', 'Kode OTP baru telah dikirim.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Bangun URL OTP verify yang aman untuk prod + dukung AJAX.
     */
private function redirectToVerify(Request $request, User $user)
{
    $next = route('verify.phone', [
        'email' => $user->email,
        'phone' => $user->phone_number,
    ]);

    // Kalau benar-benar submit via AJAX, baru kasih JSON.
    if ($request->ajax()) {
        return response()->json(['ok' => true, 'next' => $next], 200);
    }

    // Default: redirect biasa (302)
    return redirect()->to($next);
}

}
