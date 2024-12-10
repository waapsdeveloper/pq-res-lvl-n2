<?php

use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/today-deals', [HomeController::class, 'todayDeals'])->name('todayDeals');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/menu', [HomeController::class, 'menu'])->name('menu');
Route::get('/table-booking', [HomeController::class, 'tableBooking'])->name('tableBooking');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/addtocart', [HomeController::class, 'addToCart'])->name('addToCart');
