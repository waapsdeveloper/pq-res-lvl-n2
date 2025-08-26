<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ServiceResponse;   // <-- make sure you import it

class QzController extends Controller
{
    public function certificate()
    {
        try {
            $cert = Storage::disk('local')->get('qz/digital-certificate.txt');
            return ServiceResponse::success($cert, 'Certificate retrieved successfully');
        } catch (\Exception $e) {
            return ServiceResponse::error('Failed to load certificate: ' . $e->getMessage());
        }
    }

    public function sign(Request $request)
    {
        $toSign = $request->input('request', '');
        if ($toSign === '') {
            return ServiceResponse::error('Missing request to sign');
        }

        try {
            $pem = Storage::disk('local')->get('qz/private-key.pem');
            $pkey = openssl_pkey_get_private($pem);

            // Sign with SHA256 + RSA
            openssl_sign($toSign, $signature, $pkey, OPENSSL_ALGO_SHA256);

            // ✅ In PHP 8+, you do NOT need openssl_free_key anymore
            // it is deprecated because PHP automatically frees it.

            $base64 = base64_encode($signature);
            return ServiceResponse::success($base64, 'Signature generated successfully');
        } catch (\Exception $e) {
            return ServiceResponse::error('Signing failed: ' . $e->getMessage());
        }
    }
}
