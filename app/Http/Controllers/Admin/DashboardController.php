<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function orders()
    {
        $orders = Order::query()->with('restaurant', 'customer')->latest()->get();
        return ServiceResponse::success('order fetched successfully', ['order' => $orders]);
    }
    // mos setlling products
    public function products()
    {
        $products = Product::with('restaurant')->latest()->get();
        return ServiceResponse::success('products fetched successfully', ['products' => $products]);
    }
    // public function favTables(){
    //     $favTables = Order::with('restaurant', 'customer')->where('is_fav', 1)->latest()->get();
    //     return ServiceResponse::success('fav tables fetched successfully', ['favTables' => $favTables]);
    // }
    public function orderProducts()
    {
        // Sabhi products ke liye total quantity aur price ko sum karna
        $orderProducts = OrderProduct::select('product_id', DB::raw('SUM(quantity) as total_quantity'))  // Total quantity ko sum kar rahe hain
            ->groupBy('product_id')  // Product ID ke hisaab se group kar rahe hain
            ->orderByDesc('total_quantity')  // Total quantity ke hisaab se sort kar rahe hain
            ->get();

        return ServiceResponse::success('Products sorted by total quantity', ['order_products' => $orderProducts]);
    }
}
