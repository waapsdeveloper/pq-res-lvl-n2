<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Helpers\ServiceResponse;

use Storage;

class QzTrayController extends Controller
{
    public function cert()
    {
        $path = storage_path('app/certs/mycert.pem');
        $certificate = file_get_contents($path);

        // Return raw text, not JSON

        return ServiceResponse::success('certificate retrieved successfully', [
            'certificate' => $certificate
        ]);
    }


    public function sign(Request $request)
    {
        $data = $request->input('data');

        $privateKeyPath = storage_path('app/certs/private-key.pem');
        $privateKeyString = file_get_contents($privateKeyPath);

        // Load private key
        $privateKey = openssl_pkey_get_private($privateKeyString); // add passphrase as 2nd param if needed

        if (!$privateKey) {
            return ServiceResponse::error('Private key not found or invalid: ' . openssl_error_string(), 500);
        }

        // Sign the data
        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        // PHP 8+ frees key automatically

        // Return signature in ServiceResponse format
        return ServiceResponse::success('Data signed successfully', [
            'signature' => base64_encode($signature)
        ]);
    }


}
