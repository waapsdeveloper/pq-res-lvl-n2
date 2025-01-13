<?php

namespace App\Http\Middleware;

use App\Helpers\ServiceResponse;
use App\Models\Role;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error(['error' => $validator->errors()->first('email')], 400);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return ServiceResponse::error(['error' => 'User not found'], 404);
        }
        $role = Role::find($user->role_id);
        if (!$role) {
            return ServiceResponse::error(['error' => 'Role not found'], 404);
        }
        if (in_array($role->slug, ['cleaner', 'delivery-boy', 'waiter', 'customer'])) {
            return ServiceResponse::error(['error' => "You are not allowed to login here"], 403);
        }
        return $next($request);
    }
}
