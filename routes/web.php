<?php

// use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
Auth::routes(['verify' => true]);

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/listorder', function () {
//     return view('fake-order-maker');
// });
// Route::get('/api/make-order', [OrderController::class, 'orderMaker']);
// Route::get('/api/get-orders', [OrderController::class, 'getOrders']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
