<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductsController;
use App\Http\Controllers\Frontend\MessageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Frontend\RTablesController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\CouponController;
use App\Http\Controllers\Frontend\TableBookingController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ExtractRestaurantId;


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/auth/reset-passowrd', [AuthController::class, 'resetPassword']);



Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:api');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:api');


Route::middleware('auth:api')->prefix('profile')->group(function () {

    Route::post('/update-user', [ProfileController::class, 'updateUser']);
    Route::post('/update-password', [ProfileController::class, 'updatePassword']);

    Route::get('/all-user-address', [ProfileController::class, 'getUserAddresses']);
    Route::post('/add-user-address', [ProfileController::class, 'addUserAddress']);
    Route::post('/update-user-address/{id}', [ProfileController::class, 'updateUserAddress']);
    Route::delete('/delete-user-address/{id}', [ProfileController::class, 'deleteUserAddress']);

// Route::prefix('profile')->group(function () {
    Route::post('/add', [ProfileController::class, 'addProfile']);
    Route::post('/update', [ProfileController::class, 'updateProfile']);



});





Route::resource('/rtables', RTablesController::class)
    ->parameters(['' => 'id'])
    ->only(['index', 'show', 'store', 'update', 'destroy'])
    ->names('rtables');

Route::prefix('table-booking')->middleware('auth:api')->group(function () {
    // Route::get('/getRestaurantsTables', [TableBookingController::class, 'getRestaurantsTables'])->name('getRestaurantsTables');
    Route::put('on-payment', [TableBookingController::class, 'onPayment'])
        ->name('table-booking.on-payment');
    Route::get('check-table-availability/{id}', [TableBookingController::class, 'checkTableAvailability'])
        ->name('table-booking.check-availability');



    Route::resource('/', TableBookingController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('table-booking')->middleware([ExtractRestaurantId::class]);
})->middleware([ExtractRestaurantId::class]);

Route::get('/track-table-booking/{order_number}', [TableBookingController::class, 'trackTableBooking']);

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


Route::post('/contact-us', [MessageController::class, 'store'])->name('fe.contactUs.store')->middleware([ExtractRestaurantId::class]);
Route::get('/all-categories', [CategoryController::class, 'categories'])->middleware([ExtractRestaurantId::class]);
// Route::get('/restaurant/active', [HomeController::class, 'showActiveRestaurant'])->name('activeRestaurant');


Route::controller(HomeController::class)->group(function () {
    Route::get('/all-branches', 'allBranches');
    Route::get('/restautant-detail/{id}', 'restautantDetail');
    Route::get('/restaurant/active', 'showActiveRestaurant');
    Route::get('/restaurant/get-restaurant-config-by-id/{id}', 'getRestaurantConfigById');
    Route::get('/restaurant/meta/{id}', 'getRestaurantMeta');
    Route::get('/restaurant/with-meta/{id}', 'getRestaurantWithMeta');
    Route::get('/restaurant/{id}/opening-hours', 'getOpeningHours');
});

Route::middleware([ExtractRestaurantId::class])->group(function () {

    Route::controller(HomeController::class)->group(function () {
        Route::get('/get-tables-by-restaurant/{id}', 'getByRestaurantId');
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
        Route::get('/order-history', 'index')->middleware('auth:api');
        Route::post('/make-order-bookings', 'makeOrderBookings')->middleware('auth:api');
        Route::get('/search-customer-order', 'searchCustomerOrder');
        Route::get('/track-customer-order/{order_number}', 'trackCustomerOrder');
    });
});

Route::get('/countries', [CountryController::class, 'index']);

Route::prefix('coupon')->group(function () {

    Route::get('/available-valid-coupon', [CouponController::class, 'availableValidCoupon'])->name('coupon.availableValidCoupon');
    Route::post('/update-coupon-usage', [CouponController::class, 'updateCouponUsage'])->name('coupon.updateCouponUsage');
});
