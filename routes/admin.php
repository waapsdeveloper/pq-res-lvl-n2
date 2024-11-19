<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login-via-email', [AuthController::class, 'loginViaEmail']);
    Route::post('/register-via-email', [AuthController::class, 'registerViaEmail']);
});
?>
