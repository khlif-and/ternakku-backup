<?php

namespace App\Services;

use Exception;
use App\Models\Otp;
use App\Models\User;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthService
{
    public function register($validatedData)
    {
        // Begin database transaction
        DB::beginTransaction();

        try {
            // Create a new user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone_number' => $validatedData['phone_number'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Generate a new OTP
            $otp = generateOtp();

            // Save the OTP to the database
            Otp::create([
                'user_id' => $user->id,
                'code' => $otp,
                'is_used' => false,
            ]);

            // Trigger the UserRegistered event to send the OTP email
            event(new UserRegistered($user, $otp));

            // Commit the transaction
            DB::commit();

            return $user;
            // Return success response
            return ResponseHelper::success($user, 'User registered successfully', 200);

        } catch (\Exception $e) {

            // Rollback the transaction if an error occurs
            DB::rollBack();

            throw $e;
        }

    }

    public function login($validatedData)
    {
        $credentials = $validatedData->only('username', 'password');

        $user = User::where(function ($query) use ($credentials) {
            $query->where('email', $credentials['username'])
                ->orWhere('phone_number', $credentials['username']);
        })->first();

        if (!$user || !Auth::attempt(['email' => $user->email, 'password' => $credentials['password']])) {
            return [
                'error' => true,
                'message' => 'Incorrect username or password',
                'http_code' => 401,
                'data' => null
            ];
        }

        if (!$user->hasVerifiedEmail()) {
            return [
                'error' => true,
                'message' => 'Email not verified.',
                'http_code' => 403,
                'data' => null
            ];
        }

        // Generate token for API usage (optional for web, but retained here)
        $token = auth('api')->login($user);

        return [
            'error' => false,
            'message' => 'Login successful',
            'http_code' => 200,
            'data' => [
                'user' => $user,
                'token' => $token // Optional for web
            ]
        ];
    }
}
