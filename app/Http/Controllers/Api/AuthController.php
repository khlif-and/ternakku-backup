<?php

namespace App\Http\Controllers\Api;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\UserRegistered;
use Illuminate\Support\Carbon;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // Validasi input
        $validatedData = $request->validated();

        // Begin transaction
        DB::beginTransaction();

        try {
            // Buat user baru
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone_number' => $validatedData['phone_number'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Generate OTP
            $otp = generateOtp();

            // Simpan OTP ke database
            Otp::create([
                'user_id' => $user->id,
                'code' => $otp,
                'is_used' => false,
            ]);

            // Trigger event UserRegistered untuk mengirim email OTP
            event(new UserRegistered($user, $otp));

            // Commit transaction
            DB::commit();

            return ResponseHelper::success($user, 'User registered successfully', 201);
        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();
            return ResponseHelper::error('Failed to register user', 500);
        }
    }


    public function verify(VerifyOtpRequest $request)
    {
        // Validasi input
        $validatedData = $request->validated();

        // Cari user berdasarkan email
        $user = User::where('email', $validatedData['email'])->first();

        if (!$user) {
            return ResponseHelper::error('User not found', 404);
        }

        // Cari OTP yang cocok
        $otp = Otp::where('user_id', $user->id)
                    ->where('code', $validatedData['otp'])
                    ->where('is_used', false)
                    ->first();

        if (!$otp) {
            return ResponseHelper::error('Invalid OTP', 400);
        }

        // Tandai OTP sebagai telah digunakan
        $otp->is_used = true;
        $otp->save();

        // Update kolom email_verified_at pada user
        $user->email_verified_at = Carbon::now();
        $user->save();

        return ResponseHelper::success($user, 'Email verified successfully', 200);
    }

    public function resendOtp(ResendOtpRequest $request)
    {
        // Validasi input
        $validatedData = $request->validated();

        // Cari user berdasarkan email
        $user = User::where('email', $validatedData['email'])
                    ->whereNull('email_verified_at')
                    ->first();

        if (!$user) {
            return ResponseHelper::error('User not found or already verified', 404);
        }

        // Begin transaction
        DB::beginTransaction();

        try {
            // Hapus OTP yang belum digunakan sebelumnya
            Otp::where('user_id', $user->id)
                ->where('is_used', false)
                ->delete();

            // Generate OTP baru
            $otp = generateOtp();

            // Simpan OTP baru ke database
            Otp::create([
                'user_id' => $user->id,
                'code' => $otp,
                'is_used' => false,
            ]);

            // Trigger event UserRegistered untuk mengirim email OTP
            event(new UserRegistered($user, $otp));

            // Commit transaction
            DB::commit();

            return ResponseHelper::success($user, 'OTP has been resent successfully', 200);
        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();
            return ResponseHelper::error('Failed to resend OTP', 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        // Coba untuk menemukan user berdasarkan email atau nomor telepon
        $user = User::where(function($query) use ($credentials) {
            $query->where('email', $credentials['username'])
                  ->orWhere('phone_number', $credentials['username']);
        })->first();

        if (!$user || !Auth::attempt(['email' => $user->email, 'password' => $credentials['password']])) {
            return ResponseHelper::error('Incorrect username or password', 401);
        }

        // Buat token JWT untuk user
        $token = auth('api')->login($user);

        return ResponseHelper::success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ], 'Login successful');

    }
}
