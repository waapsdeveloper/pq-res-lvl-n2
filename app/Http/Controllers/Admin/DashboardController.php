<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function orders()
    {
        $orders = Order::with('restaurant', 'customer')->latest()->get();
        return ServiceResponse::success('order fetched successfully', ['order' => $orders]);
    }
}
