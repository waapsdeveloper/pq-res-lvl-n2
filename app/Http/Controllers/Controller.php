<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public static function success($msg, $data = [], $code = 200){

        $resArray = array(
            'bool' => true,
            'status' => $code,
            "message" => $msg,
            "result" => $data
        );

        // Log::info('app.requests', ['type'=> 'success', 'request' => request()->all(), 'response' => $msg]);
        return response()->json( $resArray, $code);
    }

    public static function failure($error, $data = [], $code = 400 ){

        $resArray = array(
            'bool' => false,
            'status' => $code,
            "message" => $error,
            "result" => $data
        );

        // Log::info('app.requests', ['type'=> 'error', 'request' => request()->all(), 'response' => $error]);
        return response()->json( $resArray, $code);

    }
}
