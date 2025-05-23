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
    private $authService;

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
            $user = \App\Models\User::where('email', $pendingEmail)->first();

            if ($user && is_null($user->email_verified_at)) {
                return redirect()->to('auth/verify-phone?email=' . $user->email . '&phone=' . $user->phone_number);
            }

            // Clear session kalau sudah diverifikasi
            session()->forget('pending_verify_email');
        }

        return view('admin.auth.register');
    }


    public function login(LoginRequest $request)
    {
        $response = $this->authService->login($request);

        if ($response['error']) {
            return redirect()->back()->withErrors([
                'login_error' => $response['message']
            ])->withInput();
        }

        $user = $response['data']['user'];
        Auth::login($user);

        return redirect('dashboard')->with('success', $response['message']);
    }

    public function register(RegisterRequest $request)
    {
        try {
            // Cek apakah email sudah pernah register tapi belum OTP
            $existingUser = \App\Models\User::where('email', $request->email)->first();

            if ($existingUser && is_null($existingUser->email_verified_at)) {
                // Langsung redirect ke halaman OTP, jangan buat akun baru
                return redirect()->to('auth/verify-phone?email=' . $existingUser->email . '&phone=' . $existingUser->phone_number);
            }

            // Kalau belum ada, lanjut buat akun baru
            $user = $this->authService->register($request->validated());

            // ⬇️ Tambahkan ini: assign role FARMER ke user baru
            $user->roles()->syncWithoutDetaching([
                \App\Enums\RoleEnum::FARMER->value => [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            // Simpan ke session untuk OTP
            session(['pending_verify_email' => $user->email]);

            return redirect()->to('auth/verify-phone?email=' . $user->email . '&phone=' . $user->phone_number);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'register_error' => 'Registrasi gagal: ' . $e->getMessage()
            ])->withInput();
        }
    }



    public function verifyOtp(VerifyOtpRequest $request)
    {
        // Cari user berdasarkan email
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        // Cari OTP terbaru yang cocok dan belum digunakan, dan belum expired (5 menit)
        $otp = \App\Models\Otp::where('user_id', $user->id)
            ->where('code', $request->otp)
            ->where('is_used', false)
            ->where('created_at', '>=', now()->subMinutes(5)) // optional waktu kadaluarsa
            ->orderByDesc('created_at')
            ->first();

        if (!$otp) {
            return redirect()->back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah digunakan.'])->withInput();
        }

        // Tandai OTP sudah digunakan
        $otp->update(['is_used' => true]);

        // Tandai user sebagai terverifikasi
        $user->email_verified_at = now();
        $user->save();

        // Login user
        Auth::login($user);

        session()->forget('pending_verify_email');


        return redirect('dashboard')->with('success', 'Verifikasi berhasil. Selamat datang!');
    }


    public function resendOtp(Request $request)
    {
        // Ambil email dari input POST
        $email = $request->input('email');

        // Cek user berdasarkan email
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'User tidak ditemukan.']);
        }
        do {
            $newOtp = random_int(100000, 999999);
        } while (
            Otp::where('user_id', $user->id)
            ->where('code', $newOtp)
            ->where('is_used', false)
            ->exists()
        );
        Otp::where('user_id', $user->id)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // Simpan OTP baru
        Otp::create([
            'user_id' => $user->id,
            'code' => $newOtp,
            'is_used' => false,
        ]);

        return redirect()->back()->with('success', 'Kode OTP baru telah dikirim.');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('auth/login')->with('success', 'You have been logged out successfully.');
    }
}
