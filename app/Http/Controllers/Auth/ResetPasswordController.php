<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Display the password reset view with restaurant branding.
     */
    public function showResetForm(Request $request, $token = null)
    {
        $activeRestaurant = \App\Helpers\Helper::getActiveRestaurantId();
        $restaurantLogo = $activeRestaurant ? $activeRestaurant->logo : url('/storage/restaurant/REST04.png');
        $restaurantName = $activeRestaurant ? $activeRestaurant->name : config('app.name');
        return view('auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->email,
            'restaurantLogo' => $restaurantLogo,
            'restaurantName' => $restaurantName,
        ]);
    }

    /**
     * Return JSON for AJAX password reset success.
     */
    protected function sendResetResponse(Request $request, $response)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => true, 'message' => trans($response)]);
        }
        return redirect($this->redirectPath())
            ->with('status', trans($response));
    }

    /**
     * Return JSON for AJAX password reset failure.
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        $message = trans($response);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => false, 'message' => $message], 422);
        }
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $message]);
    }
}
