<?php

namespace App\Helpers;

class ServiceResponse
{

    static public function success($msg, $data = [])
    {
        return [
            'bool' => true,
            'result' => $data,
            'message' => $msg,
        ];
    }

    static public function error($msg, $data = [])
    {
        return [
            'bool' => false,
            'message' => $msg,
            'result' => $data
        ];
    }
}
