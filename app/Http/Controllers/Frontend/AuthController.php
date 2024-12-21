<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->accessToken;

            return ServiceResponse::success('Authorized', [
                'success' => true,
                'token' => $token,
                'user' => $user,
            ]);
        }

        return ServiceResponse::error("Unauthorized", ['status' => 'false'], 401);
    }

    public function user(Request $request)
    {
        return ServiceResponse::success('Authorized', [
            'user' => $request->user()
        ]);
    }

    public function logout(Request $request)
    {
        // Current token revoke
        $request->user()->token()->revoke();

        return ServiceResponse::success('Logged out successfully', []);
    }
}
