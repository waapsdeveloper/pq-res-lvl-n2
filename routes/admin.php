<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login-via-email', [AuthController::class, 'loginViaEmail']);
    Route::post('/register-via-email', [AuthController::class, 'registerViaEmail']);
});

Route::prefix('restaurant')->group(function () {

    Route::get('/by-id/{id}', [RestaurantController::class, 'show']);
    Route::get('/list', [RestaurantController::class, 'index']);
    Route::post('/add', [RestaurantController::class, 'store']);
});






?>
