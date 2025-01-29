<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductsController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Admin\RtableController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Frontend\RTablesController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\TableBookingController;
use App\Http\Controllers\Frontend\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ExtractRestaurantId;


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

Route::prefix('add-to-cart')->group(function () {
    Route::resource('/', CartController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('cart'); // Restrict to specific CRUD actions.
});

Route::get('/restaurant-detail/{id}', [HomeController::class, 'restautantDetail']);
Route::get('/restaurant/active', [HomeController::class, 'showActiveRestaurant'])->name('activeRestaurant');
Route::controller(HomeController::class)->group(function () {
    Route::get('/restaurants', 'restaurants');
    Route::get('/restautant-detail/{id}', 'restautantDetail');
    Route::get('/show-active-restaurant', 'showActiveRestaurant');
});
Route::middleware([ExtractRestaurantId::class])->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/roles', 'roles');
        Route::get('/about-us', 'aboutUs');
        Route::get('/lowest-price', 'lowestPrice');
        Route::get('/popular-products', 'getPopularProducts');
    });

    Route::controller(ProductsController::class)->group(function () {
        Route::get('/products', 'getProducts');
        Route::get('/menu', 'menu');
        Route::get('/get-by-category/{category_id}', 'getByCategory');
        Route::get('/product-by-category/{category_id}', 'productByCategory');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::post('/make-order-bookings', 'makeOrderBookings');
        Route::get('/search-customer-order', 'searchCustomerOrder');
        Route::get('/track-customer-order/{order_number}', 'trackCustomerOrder');
    });
});


Route::get('/get-tables-by-restaurant/{id}', [RtableController::class, 'getByRestaurantId']);
Route::post('/contact-us', [ContactUsController::class, 'store'])->name('fe.contactUs.store');
Route::get('/all-categories', [CategoryController::class, 'categories']);
