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
        $data = $request->validated();

        // Retrieve the user by email
        $user = User::where('email', $data['email'])->first();

        // Check if the user exists
        if (!$user) {
            return ServiceResponse::error('Invalid Email');
        }

        // Get the user's role (assuming you have a roles table and a Role model)
        $role = $user->role ?? \App\Models\Role::find($user->role_id);

        // Block login for 'cleaner' or 'customer'
        if ($role && in_array($role->slug, ['cleaner', 'customer'])) {
            return ServiceResponse::error('You are not allowed to login here.');
        }

        // Check if the password matches
        if (!\Illuminate\Support\Facades\Hash::check($data['password'], $user->password)) {
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
    
}
