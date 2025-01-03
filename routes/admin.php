<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\RestaurantTimingController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TableBookingController;
use App\Http\Controllers\Admin\RTableBookingController;
use App\Http\Controllers\Admin\RtableController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login-via-email', [AuthController::class, 'loginViaEmail'])->name('auth.loginViaEmail');
    Route::post('/register-via-email', [AuthController::class, 'registerViaEmail'])->name('auth.registerViaEmail');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});


Route::prefix('restaurant')->group(function () {
    Route::get('/bulk-delete', [RestaurantController::class, 'bulkDelete'])->name('restaurant-bulkDelete');

    Route::resource('/', RestaurantController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('restaurant');
    Route::put('/update-favicon/{id}', [RestaurantController::class, 'updateFavicon'])
        ->name('orderUpdateFavicon');
    Route::put('/update-image/{id}', [RestaurantController::class, 'updateImage'])
        ->name('orderUpdateImage');
    Route::put('/update-logo/{id}', [RestaurantController::class, 'updateLogo'])
        ->name('orderUpdateLogo');
});


Route::prefix('restaurant-timing')->group(function () {
    Route::get('/bulk-delete', [RestaurantTimingController::class, 'bulkDelete'])->name('restaurantTiming-bulkDelete');

    Route::resource('/', RestaurantTimingController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('restaurant-timing');
});
Route::prefix('r-table-booking')->group(function () {
    Route::get('/bulk-delete', [RTableBookingController::class, 'bulkDelete'])->name('rTableBooking-bulkDelete');

    Route::resource('/', RTableBookingController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('r-table-booking');
});



Route::prefix('role')->group(function () {
    Route::get('/bulk-delete', [RoleController::class, 'bulkDelete'])->name('role-bulkDelete');

    Route::resource('/', RoleController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('role'); // Restrict to specific CRUD actions.
});

Route::prefix('user')->group(function () {
    Route::get('/bulk-delete', [UserController::class, 'bulkDelete'])->name('user-bulkDelete');

    Route::resource('/', UserController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'store', 'destroy', 'update'])
        ->names('user'); // Restrict to specific CRUD actions.
});

Route::get('/auth-user', [UserController::class, 'getAuthUser'])->middleware('auth:api');

Route::prefix('category')->group(function () {
    Route::get('/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('category-bulkDelete');

    Route::resource('/', CategoryController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('category'); // Restrict to specific CRUD actions.
});

Route::prefix('product')->group(function () {
    Route::get('/bulk-delete', [ProductController::class, 'bulkDelete'])->name('product-bulkDelete');

    Route::resource('/', ProductController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('product'); // Restrict to specific CRUD actions.
});

Route::prefix('order')->group(function () {
    Route::get('/bulk-delete', [OrderController::class, 'bulkDelete'])->name('order-bulkDelete');

    Route::resource('/', OrderController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('order');
    Route::post('/update-status/{id}', [OrderController::class, 'updateStatus'])
        ->name('orderUpdateStatus');
});


Route::prefix('rtable')->group(function () {
    Route::get('/bulk-delete', [RtableController::class, 'bulkDelete'])->name('rTable-bulkDelete');

    Route::resource('/', RtableController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'store', 'destroy', 'update',])
        ->names('rtable'); // Restrict to specific CRUD actions.
});


Route::prefix('customer')->group(function () {
    Route::get('/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('customer-bulkDelete');

    Route::resource('/', CustomerController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('customer'); // Restrict to specific CRUD actions.
});

//
Route::prefix('table-booking')->group(function () {
    Route::get('/bulk-delete', [TableBookingController::class, 'bulkDelete'])->name('tableBooking-bulkDelete');

    Route::resource('/', TableBookingController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('table-booking'); // Restrict to specific CRUD actions.
});

Route::prefix('invoice')->group(function () {
    Route::resource('/', InvoiceController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('invoice'); // Restrict to specific CRUD actions.
});
Route::prefix('contact-us')->group(function () {

    Route::get('/', [ContactUsController::class, 'index'])->name('contact.index');
    Route::get('/bulk-delete', [ContactUsController::class, 'bulkDelete'])->name('contactBulkDelete');
});


Route::prefix('dashboard')->group(function () {
    Route::get('/recent-orders', [DashboardController::class, 'recentOrders'])->name('dashboard.recentOrders');
    Route::get('/most-selling-products', [DashboardController::class, 'mostSellingProducts'])->name('dashboard.mostSellingProducts');
    Route::get('/top-selling-products', [DashboardController::class, 'topSellingProducts'])->name('dashboard.topSellingProducts');
    Route::get('/latest-tables', [DashboardController::class, 'latestTables'])->name('dashboard.latestTables');
    Route::get('/customers', [DashboardController::class, 'customerChartData'])->name('dashboard.customerChartData');
    Route::get('/sales-chart-data', [DashboardController::class, 'getSalesChartData'])->name('dashboard.salesChartData');
});
