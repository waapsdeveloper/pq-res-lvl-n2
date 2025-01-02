<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\DateHelper;
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
            ->limit(5)
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
            ->has('orders', '=', 0 || null) // Assuming users with no orders are new
            ->count();

        // Returning Customers: Customers with multiple orders or interactions
        $data['returning_customers'] = User::whereNull('role_id')
            ->has('orders', '>', 0) // Assuming users with at least one order are returning
            ->count();

        // Response
        return ServiceResponse::success('Customer registration data fetched successfully', ['customer', $data]);
    }


    public function getSalesChartData(Request $request)
    {
        $thisDate = $request->input('date', Carbon::now()->toDateString());
        $lastDate = Carbon::parse($thisDate)->subDay()->toDateString();  // Use dynamic date for last day

        // Sum product prices for this day (without quantity)
        $thisDayData = DB::table('order_products')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->select('order_products.product_id', 'products.name as category', DB::raw('SUM(order_products.quantity * products.price) as total_price'))
            ->whereDate('order_products.created_at', $thisDate)
            ->groupBy('order_products.product_id', 'products.name')
            ->orderByDesc('total_price')  // Sort by total price in descending order
            ->get()
            ->take(10);  // Limit to the top 10

        // Sum product prices for the last day (without quantity)
        $lastDayData = DB::table('order_products')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->select('order_products.product_id', 'products.name as category', DB::raw('SUM(order_products.quantity * products.price) as total_price'))
            ->whereDate('order_products.created_at', $lastDate)
            ->groupBy('order_products.product_id', 'products.name')
            ->orderByDesc('total_price')  // Sort by total price in descending order
            ->get()
            ->take(10);  // Limit to the top 10

        // Merge categories from both days, ensuring uniqueness
        $categories = $thisDayData->pluck('category')
            ->merge($lastDayData->pluck('category'))
            ->unique()
            ->values()
            ->all();

        // Initialize the series data structure
        $seriesData = [
            'This Day' => array_fill(0, count($categories), 0),  // Default to 0 for this day
            'Last Day' => array_fill(0, count($categories), 0),  // Default to 0 for last day
        ];

        // Map product prices to the correct categories (index-based)
        $categoryIndex = array_flip($categories);  // Map category names to their index positions

        // Add this day data to series data
        foreach ($thisDayData as $item) {
            $index = $categoryIndex[$item->category];
            $seriesData['This Day'][$index] = $this->formatPrice($item->total_price);
        }

        // Add last day data to series data
        foreach ($lastDayData as $item) {
            $index = $categoryIndex[$item->category];
            $seriesData['Last Day'][$index] = $this->formatPrice($item->total_price);
        }

        // Filter out products where both 'This Day' and 'Last Day' total prices are zero
        $filteredData = [];
        foreach ($categoryIndex as $category => $index) {
            if ($seriesData['This Day'][$index] > 0 || $seriesData['Last Day'][$index] > 0) {
                $filteredData[] = [
                    'category' => $category,
                    'this_day_total' => $seriesData['This Day'][$index],
                    'last_day_total' => $seriesData['Last Day'][$index]
                ];
            }
        }

        // Take only the first 10 items for both days (after filtering)
        $filteredData = array_slice($filteredData, 0, 10);

        // Prepare the final series data
        $finalCategories = array_column($filteredData, 'category');
        $thisDayTotals = array_column($filteredData, 'this_day_total');
        $lastDayTotals = array_column($filteredData, 'last_day_total');

        $responseData = [
            'categories' => $finalCategories,
            'series' => [
                [
                    'name' => 'This Day',
                    'data' => $thisDayTotals
                ],
                [
                    'name' => 'Last Day',
                    'data' => $lastDayTotals
                ]
            ]
        ];

        return ServiceResponse::success('Sales Chart Data', $responseData);
    }

    /**
     * Format the price into k, M, and B notation
     * 
     * @param float $price
     * @return string
     */
    private function formatPrice($price)
    {
        if ($price >= 1000000000) {
            return number_format($price / 1000000000, 1) . 'B';
        } elseif ($price >= 1000000) {
            return number_format($price / 1000000, 1) . 'M';
        } elseif ($price >= 1000) {
            return number_format($price / 1000, 1) . 'K';
        }

        return number_format($price, 2);  // Format for values less than 1000
    }
}
