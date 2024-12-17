<?php

use App\Http\Controllers\Frontend\TableBookingController;
use Illuminate\Support\Facades\Route;


Route::prefix('table-booking')->group(function () {
    Route::resource('/', TableBookingController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy', 'check-table-availability'])
        ->names('table-booking');

});



