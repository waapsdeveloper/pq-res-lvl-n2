<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
// Load admin-specific routes
Route::prefix('admin')->middleware('api')->group(base_path('routes/admin.php'));
Route::prefix('frontend')->middleware('api')->group(base_path('routes/frontend.php'));
Route::prefix('kiosk')->middleware('api')->group(base_path('routes/kiosk.php'));