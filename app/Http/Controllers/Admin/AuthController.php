<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginAuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

    public function loginViaEmail(LoginAuthRequest $request){

        // $data = $request->all();

        $data = $request->validated();

        // Validate the required fields
        // $validation = Validator::make($data, [
        //     'email' => 'required|email',
        //     'password' => 'required|string',
        // ]);

        // // If validation fails
        // if ($validation->fails()) {
        //     return self::failure($validation->errors()->first());
        // }

        // Retrieve the user by email
        $user = User::where('email', $data['email'])->first();

        // Check if the user exists
        if (!$user) {
            return self::failure('Invalid Email');
        }

        // Check if the password matches
        if (!Hash::check($data['password'], $user->password)) {
            return self::failure('Invalid credentials');
        }


        $token = $user->createToken('AuthToken')->accessToken;


        return self::success('Login successful', ['user' => $user, 'token' => $token]);


    }

    public function registerViaEmail(Request $request)
    {
        $data = $request->all();

        // Validate the required fields
        $validation = Validator::make($data, [
            'name' => 'required|string',
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
            'role_id' => 'required|integer|in:2,3,4,5',
        ]);

        // If validation fails
        if ($validation->fails()) {
            return self::failure($validation->errors()->first());
        }

        // Retrieve the user by email
        $user = User::where('email', $data['email'])->first();

        // Check if the user exists
        if ($user) {
            // User already exists with this email
            return self::failure('You have already signed up with this email.');
        }

        // Create a new user
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->role_id = $data['role_id'];
        $user->status = "in-review";
        $user->save();

        // Determine student or teacher role
        // if ($user->role_id == 2) {
        //     // Student
        //     $user->student()->updateOrCreate(['user_id' => $user->id], []);
        // } elseif ($user->role_id == 3) {
        //     // Teacher
        //     $user->teacher()->updateOrCreate(['user_id' => $user->id], []);
        // }

        $authAttempt = Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        if (!$authAttempt) {
            return self::failure("Authentication failed. Please check your email or password");
        }

        $token = $user->createToken('AuthToken')->accessToken;

        // if ($user->role_id == 2) {
        //     $user = new StudentResource($user);
        //     $studentName = $user->name;

        //     try {
        //         Mail::to($user->email)->send(new StudentSignup($studentName));
        //     } catch (Exception $e) {
        //         Log::debug("Email not sent correctly", ['error' => $e->getMessage()]);
        //     }
        // } elseif ($user->role_id == 3) {
        //     $user = new TeacherResource($user);
        // }

        return self::success('User registered successfully', ['user' => $user, 'token' => $token]);
    }
}
