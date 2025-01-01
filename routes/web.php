<?php

// use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/listorder', function () {
//     return view('fake-order-maker');
// });
// Route::get('/api/make-order', [OrderController::class, 'orderMaker']);
// Route::get('/api/get-orders', [OrderController::class, 'getOrders']);
