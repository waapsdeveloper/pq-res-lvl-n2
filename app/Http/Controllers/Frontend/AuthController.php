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
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required',
            'password' => 'required|string|min:8',

        ]);

        if ($validator->fails()) {
            return ServiceResponse::error($validator->errors()->first());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->accessToken;



        return ServiceResponse::success('register successful', ['user' => $user, 'token' => $token]);
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $loginKey = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $loginKey => $request->input($loginKey), // Fix: Use the correct input key
            'password' => $request->input('password')
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
        $request->validate(['email' => 'required|string']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return ServiceResponse::error('Invalid Email');
        }

        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(10);

        $userCode = UserCode::updateOrCreate(
            ['user_id' => $user->id],
            ['code' => $otp, 'expires_at' => $expiresAt]
        );
        // dd($userCode);

        try {
            Mail::to($user->email)->send(new ForgotPasswordMail($otp));
            Log::info('Email sent successfully.');
            Log::info('Generated OTP: try' . $otp);
        } catch (\Exception $e) {
            Log::info('Generated OTP: catch ' . $otp);

            Log::error('Mail error: ' . $e->getMessage());
            return ServiceResponse::error('Failed to send OTP.', $user->email);
        }

        // Mail::to($request->email)->send(new ForgotPasswordMail($otp));

        return ServiceResponse::success('OTP sent successfully', ['data' => $userCode]);
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

        return ServiceResponse::success('Password updated successfully.',['user' => $user]);
    }
}
