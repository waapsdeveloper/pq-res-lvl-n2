<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20', // Remove unique rule since guest users exist
            'password' => 'required|string|min:8',
            'dial_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error($validator->errors()->first());
        }

        // Check if user (non-guest) already exists with this phone
        $existingUser = User::where('phone', $request->phone)->whereNotNull('email')->first();
        if ($existingUser) {
            return ServiceResponse::error('Phone number or email already exists. Please log in.');
        }

        // Check if a guest user exists with the same phone
        $guestUser = User::where('phone', $request->phone)->whereNull('email')->first();

        if ($guestUser) {
            // Upgrade the guest user
            $guestUser->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'dial_code' => $request->dial_code,
                'role_id' => 10
            ]);

            $token = $guestUser->createToken('auth_token')->accessToken;

            // Send welcome email
            Mail::to($guestUser->email)->send(new \App\Mail\WelcomeMail($guestUser));

            return ServiceResponse::success('Account upgraded successfully!', [
                'user' => $guestUser,
                'token' => $token
            ]);
        }

        // Create a new user if no guest exists
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'dial_code' => $request->dial_code,
            'role_id' => 10
        ]);

        $token = $user->createToken('auth_token')->accessToken;

        // Send welcome email
        Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));

        return ServiceResponse::success('Registration successful', [
            'user' => $user,
            'token' => $token
        ]);
    }


    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $isGuestLogin = $request->input('isGuestLogin');

        if ($isGuestLogin) {

            $phone = $request->input('phone');
            $name = $request->input('name', 'Guest User');
            $dialCode = $request->input('dial_code', '+1');

            // Find or create user by phone
            $user = User::firstOrCreate(
                ['phone' => $phone],
                ['name' => $name, 'dial_code' => $dialCode, 'role_id' => 11] // Generate a random password
            );

            // Authenticate user and create token
            Auth::login($user);
            $token = $user->createToken('auth_token')->accessToken;

            return ServiceResponse::success('Guest login successful', ['user' => $user, 'token' => $token]);
        }

        // Regular login process
        $loginKey = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $loginKey => $request->input($loginKey),
            'password' => $request->input('password'),
        ];

        if (!Auth::attempt($credentials)) {
            return ServiceResponse::error("Invalid credentials");
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->accessToken;

        return ServiceResponse::success('Login successful', ['user' => $user, 'token' => $token]);
    }




    /**
     * Get the authenticated user.
     */
    public function me(Request $request)
    {
        $user = Auth::user();
        return ServiceResponse::success('Login successful', ['user' => $user]);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function forgotPassword(Request $request)
    {
        // $request->validate(['email' => 'required|string|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return ServiceResponse::error('Invalid Email');
        }

        try {
            // Send password reset link
            $status = \Illuminate\Support\Facades\Password::sendResetLink(['email' => $request->email]);

            if ($status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
                return ServiceResponse::success('Password reset link sent successfully.');
            } else {
                return ServiceResponse::error('Failed to send password reset link.');
            }
        } catch (\Exception $e) {
            Log::error('Password reset link error: ' . $e->getMessage());
            return ServiceResponse::error('An error occurred while sending the password reset link.');
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'code' => 'required|integer'
        ]);

        $userCode = UserCode::where('user_id', $request->user_id)
            ->where('code', $request->code)
            ->first();

        if (!$userCode) {
            return ServiceResponse::success('Invalid OTP.');
        }

        if ($userCode->expires_at < now()) {
            return ServiceResponse::success('OTP expired.');
        }
        $userCode->delete();

        return ServiceResponse::success('OTP verified successfully.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'password' => 'required|string  '
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return ServiceResponse::error('User not found.');
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return ServiceResponse::success('Password updated successfully.', ['user' => $user]);
    }
}
