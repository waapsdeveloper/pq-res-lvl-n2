<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductsController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Admin\RtableController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Frontend\RTablesController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\TableBookingController;
use App\Http\Controllers\Frontend\OrderController;
use Illuminate\Support\Facades\Route;



Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:api');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::get('/restaurant-detail/{id}', [HomeController::class, 'restautantDetail']);

Route::resource('/rtables', RTablesController::class)
    ->parameters(['' => 'id'])
    ->only(['index', 'show', 'store', 'update', 'destroy'])
    ->names('rtables');

Route::prefix('table-booking')->group(function () {
    // Route::get('/getRestaurantsTables', [TableBookingController::class, 'getRestaurantsTables'])->name('getRestaurantsTables');
    Route::put('on-payment', [TableBookingController::class, 'onPayment'])
        ->name('table-booking.on-payment');
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


Route::get('/products', [ProductsController::class, 'getProducts']);
Route::get('/popular-products', [ProductsController::class, 'getPopularProducts']);
Route::get('/menu', [ProductsController::class, 'menu']);
Route::get('/product-by-category/{category_id}', [ProductsController::class, 'productByCategory']);
Route::post('/contact-us', [ContactUsController::class, 'store'])->name('fe.contactUs.store');
Route::get('/today-deals', [ProductsController::class, 'todayDeals']);


Route::prefix('add-to-cart')->group(function () {
    Route::resource('/', CartController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('cart'); // Restrict to specific CRUD actions.
});
Route::post('/make-order-bookings', [OrderController::class, 'makeOrderBookings']);
Route::get('/track-customer-order/{order_number}', [OrderController::class, 'trackCustomerOrder']);
Route::post('/update-order-status', [OrderController::class, 'updateOrderStatus']);
Route::get('/roles', [HomeController::class, 'roles']);
