<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RtableController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login-via-email', [AuthController::class, 'loginViaEmail']);
    Route::post('/register-via-email', [AuthController::class, 'registerViaEmail']);
});

// Route::prefix('restaurant')->group(function () {

//     Route::get('/by-id/{id}', [RestaurantController::class, 'show']);
//     Route::get('/list', [RestaurantController::class, 'index']);
//     Route::post('/add', [RestaurantController::class, 'store']);
// });

Route::prefix('restaurant')->group(function () {
    Route::resource('/', RestaurantController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy']);
});


Route::prefix('role')->group(function () {
    Route::resource('/', RoleController::class)
        ->only(['index']); // Restrict to specific CRUD actions.
});

Route::prefix('user')->group(function () {
    Route::resource('/', UserController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'store', 'destroy']); // Restrict to specific CRUD actions.
});

Route::get('/auth-user', [UserController::class, 'getAuthUser'])->middleware('auth:api');

Route::prefix('category')->group(function () {
    Route::resource('/', CategoryController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy']); // Restrict to specific CRUD actions.
});

Route::prefix('product')->group(function () {
    Route::resource('/', ProductController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy']); // Restrict to specific CRUD actions.
});

Route::prefix('rtable')->group(function () {
    Route::resource('/', RtableController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'store', 'destroy']); // Restrict to specific CRUD actions.
});
