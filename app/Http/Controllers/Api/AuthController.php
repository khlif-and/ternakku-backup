<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Events\UserRegistered;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // Validasi input request
        $validatedData = $request->validated();

        // Membuat user baru
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Generate OTP
        $otp = generateOtp();

        // Trigger event UserRegistered
        event(new UserRegistered($user, $otp));

        // Return a response or redirect as needed
        return ResponseHelper::success($user, 'User registered successfully' , 201);
    }
}
