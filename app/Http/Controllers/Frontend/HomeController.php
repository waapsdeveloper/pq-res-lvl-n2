<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function menu()
    {
        return view('menu');
    }
    public function addToCart()
    {
        return view('add_to_cart');
    }
    public function todayDeals()
    {
        return view('today_deals');
    }
    public function contact()
    {
        return view('contact');
    }
    public function about()
    {
        return view('about');
    }
    public function tableBooking()
    {
        return view('table_booking');
    }
}
