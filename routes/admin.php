<?php

use App\Helpers\Helper;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\RestaurantSettingController;
use App\Http\Controllers\Admin\RestaurantTimingController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TableBookingController;
use App\Http\Controllers\Admin\RTableBookingController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\RtableController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VariationController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\BranchConfigController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;



Route::prefix('auth')->middleware([
    AuthMiddleware::class . ':admin,user',
])->group(function () {
    Route::post('/login-via-email', [AuthController::class, 'loginViaEmail'])->name('auth.loginViaEmail');
    Route::post('/register-via-email', [AuthController::class, 'registerViaEmail'])->name('auth.registerViaEmail');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});


Route::prefix('restaurant')->group(function () {

    Route::post('/setting', [RestaurantController::class, 'setting'])->name('restaurantSettings');

    Route::get('/bulk-delete', [RestaurantController::class, 'bulkDelete'])->name('restaurant-bulkDelete');

    Route::get('/active', [RestaurantController::class, 'showActiveRestaurant'])->name('activeRestaurant');
    Route::put('/update-active/{id}', [RestaurantController::class, 'updateActiveRestaurant'])->name('updateactiveRestaurant');
    Route::get('/active', [RestaurantController::class, 'showActiveRestaurant'])->name('activeRestaurant');

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
    
    // Restaurant Meta routes
    Route::post('/{id}/meta', [RestaurantController::class, 'storeMeta'])->name('restaurant.storeMeta');
    Route::get('/{id}/meta', [RestaurantController::class, 'getMeta'])->name('restaurant.getMeta');
    Route::delete('/{id}/meta', [RestaurantController::class, 'deleteMeta'])->name('restaurant.deleteMeta');
});



Route::prefix('restaurant-timing')->group(function () {
    Route::get('/bulk-delete', [RestaurantTimingController::class, 'bulkDelete'])->name('restaurantTiming-bulkDelete');
    Route::get('/config', [RestaurantTimingController::class, 'getTimingConfig'])->name('restaurantTiming-config');
    Route::get('/data', [RestaurantTimingController::class, 'getTimingData'])->name('restaurantTiming-data');
    Route::post('/config', [RestaurantTimingController::class, 'store'])->name('restaurantTiming-storeConfig');
    Route::put('/config/{id}', [RestaurantTimingController::class, 'update'])->name('restaurantTiming-updateConfig');
    Route::post('/check-open-status', [RestaurantTimingController::class, 'checkOpenStatus'])->name('restaurantTiming-checkOpenStatus');

    Route::resource('/', RestaurantTimingController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('restaurant-timing');
});
Route::prefix('table-booking')->group(function () {
    Route::get('/bulk-delete', [RTableBookingController::class, 'bulkDelete'])->name('rTableBooking-bulkDelete');

    Route::resource('/', RTableBookingController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('table-booking');
    Route::put('/update-status/{id}', [RTableBookingController::class, 'updateStatus'])
        ->name('tablebookingUpdateStatus');
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
Route::get('/auth-user/permissions', [UserController::class, 'getAuthUserPermissions'])->middleware('auth:api');

Route::prefix('category')->group(function () {
    Route::get('/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('category-bulkDelete');
    
    // Route for existing category image upload with category ID
    Route::post('/{id}/upload-image', [CategoryController::class, 'uploadImage'])->name('category.uploadImage');

    Route::resource('/', CategoryController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('category'); // Restrict to specific CRUD actions.
});

Route::prefix('product')->group(function () {
    Route::get('/bulk-delete', [ProductController::class, 'bulkDelete'])->name('product-bulkDelete');
    Route::get('/bulk-fetch', [ProductController::class, 'bulkFetch'])->name('product-bulkFetch');
    
    // Route for existing product image upload with product ID
    Route::post('/{id}/upload-image', [ProductController::class, 'uploadImage'])->name('product.uploadImage');

    Route::resource('/', ProductController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('product'); // Restrict to specific CRUD actions.
});

Route::prefix('order')->group(function () {
    Route::get('/totals', [OrderController::class, 'totals'])->name('order.totals');
    Route::get('/bulk-delete', [OrderController::class, 'bulkDelete'])->name('order-bulkDelete');

    Route::resource('/', OrderController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('order');
    Route::put('/update-status/{id}', [OrderController::class, 'updateStatus'])
        ->name('orderUpdateStatus');
    Route::put('/update-payment-status/{id}', [OrderController::class, 'updatePaymentStatus'])
        ->name('orderUpdatePaymentStatus');
});

Route::prefix('rtable')->group(function () {
    Route::get('/bulk-delete', [RtableController::class, 'bulkDelete'])->name('rTable-bulkDelete');

    Route::resource('/', RtableController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'store', 'destroy', 'update',])
        ->names('rtable'); // Restrict to specific CRUD actions.
    Route::put('/update-status/{id}', [RtableController::class, 'updateStatus'])
        ->name('tableUpdateStatus');
});


Route::prefix('customer')->group(function () {
    Route::get('/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('customer-bulkDelete');

    Route::resource('/', CustomerController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('customer'); // Restrict to specific CRUD actions.
});
Route::prefix('variation')->group(function () {
    Route::get('/bulk-delete', [VariationController::class, 'bulkDelete'])->name('variation-bulkDelete');

    Route::resource('/', VariationController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('customer'); // Restrict to specific CRUD actions.
});

// DUPLICATE API CALLS

// Route::prefix('table-booking')->group(function () {
//     Route::get('/bulk-delete', [TableBookingController::class, 'bulkDelete'])->name('tableBooking-bulkDelete');

//     Route::resource('/', TableBookingController::class)
//         ->parameters(['' => 'id']) // If needed, customize parameter names.
//         ->only(['index', 'show', 'update', 'store', 'destroy'])
//         ->names('table-booking'); // Restrict to specific CRUD actions.
// });

Route::prefix('invoice')->group(function () {
    Route::resource('/', InvoiceController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('invoice'); // Restrict to specific CRUD actions.
    Route::put('/update-status/{id}', [InvoiceController::class, 'updateStatus'])
        ->name('invoiceUpdateStatus');
});
Route::prefix('message')->group(function () {
    Route::put('/reply/{email}', [MessageController::class, 'reply'])->name('message.reply');
    Route::put('/update-status', [MessageController::class, 'updateStatus'])->name('message.updateStatus');


    Route::get('/bulk-delete', [MessageController::class, 'bulkDelete'])->name('message.BulkDelete');
    Route::resource('/', MessageController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('invoice');
});


Route::prefix('dashboard')->group(function () {
    Route::get('/recent-orders', [DashboardController::class, 'recentOrders'])->name('dashboard.recentOrders');
    Route::get('/most-selling-products', [DashboardController::class, 'mostSellingProducts'])->name('dashboard.mostSellingProducts');
    Route::get('/top-selling-products', [DashboardController::class, 'topSellingProducts'])->name('dashboard.topSellingProducts');
    Route::get('/latest-tables', [DashboardController::class, 'latestTables'])->name('dashboard.latestTables');
    Route::get('/customers', [DashboardController::class, 'customerChartData'])->name('dashboard.customerChartData');
    Route::get('/sales-chart-data', [DashboardController::class, 'getSalesChartData'])->name('dashboard.salesChartData');
    Route::get('/sales-summary', [DashboardController::class, 'salesSummary'])->name('dashboard.salesSummary');
    Route::get('/total-revenue', [DashboardController::class, 'totalRevenue'])->name('dashboard.totalRevenue');
});
Route::prefix('notifications')->group(function () {
    Route::get('/all', [NotificationController::class, 'getNotifications']);
    // Route::get('/unread', [NotificationController::class, 'getUnreadNotifications']);
    // Route::post('/send-notification/{adminId}/{orderId}', [NotificationController::class, 'sendNotification']);
    Route::post('/mark-as-read/{notificationId}', [NotificationController::class, 'markAsRead']);
    Route::get('/show/{notificationId}', [NotificationController::class, 'show']);
});
Route::get('/dashboard-top-cards', [DashboardController::class, 'dashboardTopCards'])->name('dashboard.topCards');
Route::prefix('coupon')->group(function () {

    Route::get('/available-valid-coupon', [CouponController::class, 'availableValidCoupon'])->name('coupon.availableValidCoupon');
    Route::post('/update-coupon-usage', [CouponController::class, 'updateCouponUsage'])->name('coupon.updateCouponUsage');
    Route::get('/bulk-delete', [CouponController::class, 'bulkDelete'])->name('coupon.BulkDelete');
    Route::resource('/', CouponController::class)
        ->parameters(['' => 'id']) // If needed, customize parameter names.
        ->only(['index', 'show', 'update', 'store', 'destroy'])
        ->names('coupon'); // Restrict to specific CRUD actions.
});

Route::prefix('branch-config')->group(function () {
    Route::get('/', [BranchConfigController::class, 'index'])->name('branchConfig.index');
    Route::resource('/', BranchConfigController::class)
        ->parameters(['' => 'id'])
        ->only(['show', 'update', 'store', 'destroy'])
        ->names('branchConfig');
    Route::get('/create', [BranchConfigController::class, 'create'])->name('branchConfig.create');
    Route::get('/get-config-by-branch-id/{branchId}', [BranchConfigController::class, 'getRestaurantConfigById'])->name('branchConfig.get-config-by-branch-id');
});

Route::prefix('currency')->group(function () {
    Route::get('/', [CurrencyController::class, 'index'])->name('currency.index');
});


Route::prefix('expense-category')->group(function () {
    Route::resource('/', ExpenseCategoryController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('expense-category');
});

Route::prefix('expense')->group(function () {
    Route::resource('/', \App\Http\Controllers\Admin\ExpenseController::class)
        ->parameters(['' => 'id'])
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('expense');
    Route::put('/update-status/{id}', [\App\Http\Controllers\Admin\ExpenseController::class, 'updateStatus'])
        ->name('expense.updateStatus');
    Route::put('/update-type/{id}', [\App\Http\Controllers\Admin\ExpenseController::class, 'updateType'])
        ->name('expense.updateType');
});
