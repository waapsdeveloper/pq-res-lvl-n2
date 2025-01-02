<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\Rtable;
use App\Models\RTablesBooking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function recentOrders()
    {
        $orders = Order::query()
            // ->with('restaurant', 'customer')
            // ->orderByDesc('id')
            ->latest()->limit(10)->get();
        return ServiceResponse::success('order fetched successfully', ['order' => $orders]);
    }

    public function mostSellingProducts()
    {
        // Sabhi products ke liye total quantity aur price ko sum karna
        $orderProducts = OrderProduct::select('product_id', DB::raw('SUM(quantity) as total_quantity_sell'))  // Total quantity ko sum kar rahe hain
            ->groupBy('product_id')  // Product ID ke hisaab se group kar rahe hain
            ->orderByDesc('total_quantity_sell')  // Total quantity ke hisaab se sort kar rahe hain
            ->with('product')  // Product model ke saath join kar rahe hain
            ->get();

        $data = $orderProducts->map(function ($orderProduct) {
            $product = $orderProduct->product;
            $product->total_quantity_sell = $orderProduct->total_quantity_sell;
            $product->image = Helper::returnFullImageUrl($product->image);
            return $product;
        });

        return ServiceResponse::success('Products sorted by total quantity', ['order_products' => $data]);
    }
    public function topSellingProducts()
    {
        // Sabhi products ke liye total quantity aur price ko sum karna
        $orderProducts = OrderProduct::select('product_id', DB::raw('SUM(quantity) as total_quantity_sell'))  // Total quantity ko sum kar rahe hain
            ->groupBy('product_id')  // Product ID ke hisaab se group kar rahe hain
            ->orderByDesc('total_quantity_sell')  // Total quantity ke hisaab se sort kar rahe hain
            ->with('product')  // Product model ke saath join kar rahe hain
            ->limit(5)  // Limit 5 products
            ->get();

        return ServiceResponse::success('Products sorted by total quantity', ['order_products' => $orderProducts]);
    }
    public function totalRevenue()
    {
        $totalRevenue = Order::sum('total_price');
        return ServiceResponse::success('Total revenue fetched successfully', ['total_revenue' => $totalRevenue]);
    }
    public function latestTables()
    {
        $tables = Rtable::with('restaurantDetail')->limit(8)->latest()->get();
        return ServiceResponse::success('Tables fetched successfully', ['tables' => $tables]);
    }
    public function customer()
    {
        $data = []; // Final organized data

        // Total customers (role_id null wale, yani sirf customers)
        $data['total_customers'] = User::whereNull('role_id')->count();

        // Daily data: Group by day
        $data['daily_data'] = User::whereNull('role_id')
            ->select(DB::raw('DATE(created_at) as on_date'), DB::raw('COUNT(*) as total_customers'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        // Weekly data: Group by week
        $data['weekly_data'] = User::whereNull('role_id')
            ->select(DB::raw('WEEK(created_at) as in_weeks'), DB::raw('COUNT(*) as total_customers'))
            ->groupBy(DB::raw('WEEK(created_at)'))
            ->orderBy(DB::raw('WEEK(created_at)'))
            ->get();

        // Monthly data: Group by month
        $data['monthly_data'] = User::whereNull('role_id')
            ->select(DB::raw('MONTH(created_at) as in_months'), DB::raw('COUNT(*) as total_customers'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Yearly data: Group by year
        $data['yearly_data'] = User::whereNull('role_id')
            ->select(DB::raw('YEAR(created_at) as in_year'), DB::raw('COUNT(*) as total_customers'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'))
            ->get();
        $data['new_customers'] = User::whereNull('role_id')
            ->has('orders', '=', 0) // Assuming users with no orders are new
            ->count();

        // Returning Customers: Customers with multiple orders or interactions
        $data['returning_customers'] = User::whereNull('role_id')
            ->has('orders', '>', 0) // Assuming users with at least one order are returning
            ->count();

        // Response
        return ServiceResponse::success('Customer registration data fetched successfully', ['customer', $data]);
    }
    public function getSalesChartData()
    {
        $thisDate = Carbon::now()->toDateString();
        $lastDate = Carbon::yesterday()->toDateString();

        // Count product_id occurrences for the this day with product names
        $thisDayData = DB::table('order_products')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->select('order_products.product_id', 'products.name as category', DB::raw('COUNT(order_products.product_id) as count'))
            ->whereDate('order_products.created_at', $thisDate)
            ->groupBy('order_products.product_id', 'products.name')
            ->orderBy('count', 'desc')
            ->get();

        // Count product_id occurrences for the last day with product names
        $lastDayData = DB::table('order_products')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->select('order_products.product_id', 'products.name as category', DB::raw('COUNT(order_products.product_id) as count'))
            ->whereDate('order_products.created_at', $lastDate)
            ->groupBy('order_products.product_id', 'products.name')
            ->orderBy('count', 'desc')
            ->get();

        $categories = $thisDayData->pluck('category')
            ->merge($lastDayData->pluck('category'))
            ->unique()
            ->values()
            ->all();

        $productsData = [];

        // Add this day data to the product array
        foreach ($thisDayData as $item) {
            $productsData[$item->product_id]['product_id'] = $item->product_id;
            $productsData[$item->product_id]['product_name'] = $item->category;
            $productsData[$item->product_id]['this_day_count'] = $item->count;
        }

        // Add last day data to the product array
        foreach ($lastDayData as $item) {
            if (!isset($productsData[$item->product_id])) {
                $productsData[$item->product_id] = [
                    'product_id' => $item->product_id,
                    'product_name' => $item->category,
                    'this_day_count' => 0, // Default to 0 if not present in the this day
                ];
            }
            $productsData[$item->product_id]['last_day_count'] = $item->count;
        }

        // Ensure all products have 'last_day_count' set to 0 if not already set
        foreach ($productsData as $productId => &$data) {
            if (!isset($data['last_day_count'])) {
                $data['last_day_count'] = 0;
            }
        }

        // Convert products data to an array for response
        $productsDataArray = array_values($productsData);
        return ServiceResponse::success('Sales Chart Data', [
            'categories' => $categories,
            'products' => $productsDataArray
        ]);
    }
    // public function 
}
