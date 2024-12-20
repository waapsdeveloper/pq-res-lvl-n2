<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\RestaurantTimingController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RTableBookingController;
use App\Http\Controllers\Admin\RtableController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login-via-email', [AuthController::class, 'loginViaEmail'])->name('auth.loginViaEmail');
    Route::post('/register-via-email', [AuthController::class, 'registerViaEmail'])->name('auth.registerViaEmail');
});

// Route::prefix('restaurant')->group(function () {

//     Route::get('/by-id/{id}', [RestaurantController::class, 'show']);
//     Route::get('/list', [RestaurantController::class, 'index']);
//     Route::post('/add', [RestaurantController::class, 'store']);
// });

Route::prefix('restaurant')->group(function () {
    Route::resource('/', RestaurantController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('restaurant');
});
Route::prefix('restaurant-timing')->group(function () {
    Route::resource('/', RestaurantTimingController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('restaurant-timing');
});
Route::prefix('r-table-booking')->group(function () {
    Route::resource('/', RTableBookingController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('r-table-booking');
});



Route::prefix('role')->group(function () {
    Route::resource('/', RoleController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('role'); // Restrict to specific CRUD actions.
});

Route::prefix('user')->group(function () {
    Route::resource('/', UserController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'store', 'destroy', 'update'])
        ->names('user'); // Restrict to specific CRUD actions.
});

Route::get('/auth-user', [UserController::class, 'getAuthUser'])->middleware('auth:api');

Route::prefix('category')->group(function () {
    Route::resource('/', CategoryController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('category'); // Restrict to specific CRUD actions.
});

Route::prefix('product')->group(function () {
    Route::resource('/', ProductController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('product'); // Restrict to specific CRUD actions.
});

Route::prefix('order')->group(function () {
    Route::resource('/', OrderController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('order');
    Route::post('/update-status/{id}', [OrderController::class, 'updateStatus'])
        ->name('orderUpdateStatus');
});



Route::prefix('rtable')->group(function () {
    Route::resource('/', RtableController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'store', 'destroy', 'update',])
        ->names('rtable'); // Restrict to specific CRUD actions.
});


Route::prefix('customer')->group(function () {
    Route::resource('/', CustomerController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('customer'); // Restrict to specific CRUD actions.
});
