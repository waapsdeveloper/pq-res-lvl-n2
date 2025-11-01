<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Kiosk\KioskController;


Route::get('/catalog', [KioskController::class, 'getCatalog']);
Route::get('/restaurant-meta', [KioskController::class, 'getRestaurantMeta']);
Route::post('/create-order', [KioskController::class, 'createOrder']);