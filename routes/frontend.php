<?php

use App\Http\Controllers\Admin\RtableController;
use App\Http\Controllers\Frontend\RTablesController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\TableBookingController;
use Illuminate\Support\Facades\Route;



Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:api');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:api');


Route::resource('/rtables', RTablesController::class)
    ->parameters(['' => 'id'])
    ->only(['index', 'show', 'store', 'update', 'destroy'])
    ->names('rtables');


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
