<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
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

        // Return a response or redirect as needed
        return ResponseHelper::success($user, 'User registered successfully' , 201);
    }
}
