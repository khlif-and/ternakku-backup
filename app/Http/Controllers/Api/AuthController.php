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
    /**
     * Handle the user registration process.
     *
     * This method validates the user input, creates a new user, generates an OTP,
     * saves the OTP to the database, and triggers an event to send the OTP email.
     * The process is wrapped in a database transaction to ensure data consistency.
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        // Validate the request input
        $validatedData = $request->validated();

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
            // event(new UserRegistered($user, $otp));

            // Commit the transaction
            DB::commit();

            // Return success response
            return ResponseHelper::success($user, 'User registered successfully', 201);
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();
            return ResponseHelper::error('Failed to register user', 500);
        }
    }

    /**
     * Handle the OTP verification process.
     *
     * This method validates the input, verifies the OTP for the user, marks the OTP as used,
     * and updates the user's email verification timestamp if the OTP is valid.
     *
     * @param  \App\Http\Requests\VerifyOtpRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(VerifyOtpRequest $request)
    {
        // Validate the request input
        $validatedData = $request->validated();

        // Find the user based on the email
        $user = User::where('email', $validatedData['email'])->first();

        // Return error if user not found
        if (!$user) {
            return ResponseHelper::error('User not found', 404);
        }

        // Check if the environment is development and OTP is '123456'
        if (config('app.env') == 'development' && $validatedData['otp'] == '123456') {
            // Do nothing as the OTP is whitelisted in development environment
        }else{
            // Find the OTP that matches the user ID and code, and is not used
            $otp = Otp::where('user_id', $user->id)
                    ->where('code', $validatedData['otp'])
                    ->where('is_used', false)
                    ->first();

            // Return error if OTP is invalid
            if (!$otp) {
                return ResponseHelper::error('Invalid OTP', 400);
            }

            // Mark the OTP as used
            $otp->is_used = true;
            $otp->save();
        }

        // Update the user's email_verified_at column
        $user->email_verified_at = Carbon::now();
        $user->save();

        // Return success response
        return ResponseHelper::success($user, 'Email verified successfully', 200);
    }

    /**
     * Handle the OTP resend process.
     *
     * This method validates the input, finds the user, deletes any unused OTPs,
     * generates a new OTP, saves it to the database, and triggers an event to resend the OTP email.
     * The process is wrapped in a database transaction to ensure data consistency.
     *
     * @param  \App\Http\Requests\ResendOtpRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendOtp(ResendOtpRequest $request)
    {
        // Validate the request input
        $validatedData = $request->validated();

        // Find the user based on the email
        $user = User::where('email', $validatedData['email'])
                    ->whereNull('email_verified_at')
                    ->first();

        // Return error if user not found or already verified
        if (!$user) {
            return ResponseHelper::error('User not found or already verified', 404);
        }

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Delete any unused OTPs for the user
            Otp::where('user_id', $user->id)
                ->where('is_used', false)
                ->delete();

            // Generate a new OTP
            $otp = generateOtp();

            // Save the new OTP to the database
            Otp::create([
                'user_id' => $user->id,
                'code' => $otp,
                'is_used' => false,
            ]);

            // Trigger the UserRegistered event to resend the OTP email
            // event(new UserRegistered($user, $otp));

            // Commit the transaction
            DB::commit();

            // Return success response
            return ResponseHelper::success($user, 'OTP has been resent successfully', 200);
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();
            return ResponseHelper::error('Failed to resend OTP', 500);
        }
    }

    /**
     * Handle the login process.
     *
     * This method validates the input, attempts to authenticate the user based on their email or phone number,
     * generates a JWT token if authentication is successful, and returns the token in the response.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        // Get the credentials from the request
        $credentials = $request->only('username', 'password');

        // Attempt to find the user by email or phone number
        $user = User::where(function($query) use ($credentials) {
            $query->where('email', $credentials['username'])
                ->orWhere('phone_number', $credentials['username']);
        })->first();

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            return ResponseHelper::error('Email not verified.', 403);
        }

        // Return error if user not found or authentication fails
        if (!$user || !Auth::attempt(['email' => $user->email, 'password' => $credentials['password']])) {
            return ResponseHelper::error('Incorrect username or password', 401);
        }

        // Generate a JWT token for the user
        $token = auth('api')->login($user);

        // Return success response with the token
        return ResponseHelper::success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ], 'Login successful');
    }

    /**
     * Get the authenticated user's details.
     *
     * This method retrieves the authenticated user's details
     * and returns them in the response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        // Get the authenticated user
        $user = auth('api')->user();

        // Return success response with user details
        return ResponseHelper::success($user, 'User details retrieved successfully', 200);
    }

    /**
     * Log the user out (invalidate the token).
     *
     * This method invalidates the JWT token, effectively logging the user out.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            // Invalidate the token
            auth('api')->logout();

            // Return success response
            return ResponseHelper::success(null, 'Successfully logged out', 200);
        } catch (\Exception $e) {
            // Return error response if logout fails
            return ResponseHelper::error('Failed to log out', 500);
        }
    }
}
