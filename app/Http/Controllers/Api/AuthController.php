<?php

namespace App\Http\Controllers\Api;

use App\Models\Otp;
use App\Models\User;
use App\Enums\RoleEnum;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Events\UserRegistered;
use Illuminate\Support\Carbon;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Events\ForgotPasswordEvent;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
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

        try {
            $user = $this->authService->register($validatedData);

            // Return success response
            return ResponseHelper::success($user, 'User registered successfully', 200);

        } catch (\Exception $e) {

            return ResponseHelper::error($e->getMessage(), 500);
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

        $token = auth('api')->login($user);

        $user->roles()->attach(RoleEnum::REGISTERED_USER->value, [
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Return success response with the token
        return ResponseHelper::success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ], 'Login successful');
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
            event(new UserRegistered($user, $otp));

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

        // Return error if user not found or authentication fails
        if (!$user || !Auth::attempt(['email' => $user->email, 'password' => $credentials['password']])) {
            return ResponseHelper::error('Incorrect username or password', 401);
        }

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            return ResponseHelper::error('Email not verified.', 403);
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
        $user = auth('api')->user()->load('roles:id,name');

        $user->roles->makeHidden('pivot');

        // Return success response with user details
        return ResponseHelper::success(new UserResource($user), 'User details retrieved successfully', 200);
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

    public function updateProfile(ProfileUpdateRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $user = auth()->user()->load('profile');

            $user->update([
                'name' => $validated['name'],
            ]);

            $profile = $user->profile;

            if (isset($validated['photo']) && $request->hasFile('photo')) {

                if ($profile && $profile->photo) {
                    deleteNeoObject($profile->photo);
                }

                $file = $validated['photo'];
                $fileName = time() . '-profile-' . $file->getClientOriginalName();
                $filePath = 'profile/';
                $profileData['photo'] = uploadNeoObject($file, $fileName, $filePath);

            }

            if($profile){
                $profile->update($profileData);
            }else{
                $profileData['user_id'] = $user->id;
                Profile::create($profileData);
            }

            DB::commit();

            return ResponseHelper::success(new UserResource($user), 'Profile updated successfully', 200);

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to update profile: ' . $e->getMessage(), 500);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // Validate the request input
        $validatedData = $request->validated();

        // Find the user based on the email
        $user = User::where('phone_number', $validatedData['phone_number'])
                    ->whereNotNull('email_verified_at')
                    ->first();

        // Return error if user not found
        if (!$user) {
            return ResponseHelper::error('User not found', 404);
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

            event(new ForgotPasswordEvent($user, $otp));

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

    public function resetPassword(ResetPasswordRequest $request)
    {
        // Validate the request input
        $validatedData = $request->validated();

        // Find the user based on the phone number
        $user = User::where('phone_number', $validatedData['phone_number'])->first();

        // Return error if user not found
        if (!$user) {
            return ResponseHelper::error('User not found', 404);
        }

        // Find the OTP that matches the user ID and code, and is not used
        $otp = Otp::where('user_id', $user->id)
                ->where('code', $validatedData['otp'])
                ->where('is_used', false)
                ->first();

        // Return error if OTP is invalid
        if (!$otp) {
            return ResponseHelper::error('Invalid OTP', 400);
        }

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Update the user's password
            $user->password = Hash::make($validatedData['password']);
            $user->save();

            // Mark the OTP as used
            $otp->is_used = true;
            $otp->save();

            // Commit the transaction
            DB::commit();

            return ResponseHelper::success([
                'access_token' => auth('api')->login($user),
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ], 'Password reset successfully');

        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();
            return ResponseHelper::error('Failed to reset password', 500);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        // Validate the request input
        $validatedData = $request->validated();

        // Get the currently authenticated user
        $user = auth()->user();

        // Check if the current password matches
        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return ResponseHelper::error('Current password is incorrect', 400);
        }

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Update the user's password
            $user->password = Hash::make($validatedData['new_password']);
            $user->save();

            // Commit the transaction
            DB::commit();

            return ResponseHelper::success(null, 'Password changed successfully', 200);

        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();
            return ResponseHelper::error('Failed to change password', 500);
        }
    }
}
