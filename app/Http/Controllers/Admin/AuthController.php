<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginAuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
use App\Helpers\ServiceResponse;
use App\Mail\ForgotUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\PasswordReset;


class AuthController extends Controller
{
    //

    public function loginViaEmail(LoginAuthRequest $request)
    {

        // $data = $request->all();

        $data = $request->validated();


        // Retrieve the user by email
        $user = User::where('email', $data['email'])->first();

        // Check if the user exists
        if (!$user) {
            return ServiceResponse::error('Invalid Email');
        }

        // Check if the password matches
        if (!Hash::check($data['password'], $user->password)) {
            return ServiceResponse::error('Invalid credentials');
        }


        $token = $user->createToken('AuthToken')->accessToken;


        return ServiceResponse::success('Login successful', ['user' => $user, 'token' => $token]);
    }

    public function registerViaEmail(LoginAuthRequest $request)
    {
        // $data = $request->all();
        $data = $request->validated();



        // Retrieve the user by email
        $user = User::where('email', $data['email'])->first();

        // Check if the user exists
        if ($user) {
            // User already exists with this email
            return ServiceResponse::error('You have already signed up with this email.');
        }

        // Create a new user
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->role_id = $data['role_id'];
        $user->status = "in-review";
        $user->save();



        $authAttempt = Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        if (!$authAttempt) {
            return ServiceResponse::error("Authentication failed. Please check your email or password");
        }

        $token = $user->createToken('AuthToken')->accessToken;



        return ServiceResponse::success('User registered successfully', ['user' => $user, 'token' => $token]);
    }
    public function forgotPassword(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return ServiceResponse::error('User not found');
        }
        $token = Str::upper(Str::random(40));
        if ($user) {
            $user->update(['token' => $token]);
            $duplicationToken = PasswordReset::where('email', $user->email)->first();
            if ($duplicationToken) {
                $duplicationToken->delete();
            }
            PasswordReset::create([
                'email' => $user->email,
                'token' => $token,
                'created_at' => now(),
                'updated_at' => false,
            ]);

            Mail::to($user->email)->send(new ForgotUser($user, $token));

            return ServiceResponse::success('Password reset link sent to your email.');
        }
    }

    public function resetPassword(Request $request)
    {
        // Validate the input (token, email, password, password_confirmation)
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string', // New password
        ]);

        // Attempt to reset the password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Update the user password
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        // Check if the password reset was successful
        if ($status === Password::PASSWORD_RESET) {
            return ServiceResponse::success('Password reset successfully.');
        }

        return ServiceResponse::error('Failed to reset password. Please try again.');
    }
}
