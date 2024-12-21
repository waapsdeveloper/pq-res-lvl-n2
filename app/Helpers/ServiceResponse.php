<?php

namespace App\Helpers;

class ServiceResponse
{

    static public function success($msg, $data = [], $code = 200)
    {
        return [
            'bool' => true,
            'result' => $data,
            'message' => $msg,
            'code' => $code
        ];
    }

    static public function error($msg, $data = [], $code = 400)
    {
        return [
            'bool' => false,
            'message' => $msg,
            'result' => $data,
            'code' => $code
        ];
    }
}
