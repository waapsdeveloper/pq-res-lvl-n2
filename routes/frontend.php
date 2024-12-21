<?php

use App\Http\Controllers\Admin\RtableController;
use App\Http\Controllers\Frontend\RTablesController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\TableBookingController;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::prefix('table-booking')->group(function () {
    // Route::get('/getRestaurantsTables', [TableBookingController::class, 'getRestaurantsTables'])->name('getRestaurantsTables');
    Route::get('check-table-availability/{id}', [TableBookingController::class, 'checkTableAvailability'])
        ->name('table-booking.check-availability');

    Route::resource('/', TableBookingController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('table-booking');
});

Route::resource('/rtables', RTablesController::class)
    ->parameters(['' => 'id'])
    ->only(['index', 'show', 'store', 'update', 'destroy'])
    ->names('rtables');

Route::get('/get-tables-by-restaurant/{id}', [RtableController::class, 'getByRestaurantId']);
