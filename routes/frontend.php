<?php

use App\Http\Controllers\Frontend\TableBookingController;
use Illuminate\Support\Facades\Route;


Route::prefix('table-booking')->group(function () {


    // Route::get('/getRestaurantsTables', [TableBookingController::class, 'getRestaurantsTables'])->name('getRestaurantsTables');
    Route::post('check-table-availability', [TableBookingController::class, 'checkTableAvailability'])
        ->name('table-booking.check-availability');


    Route::resource('/', TableBookingController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('table-booking');

    // Add your custom route here
    // Route::post('check-table-availability', [TableBookingController::class, 'checkTableAvailability'])
    //     ->name('table-booking.check-availability');
});
