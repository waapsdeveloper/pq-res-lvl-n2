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
        // Get the date from the request or default to today's date
        $Date = $request->input('date', Carbon::now()->toDateString());
        $selectedDate = Carbon::parse($Date);
        $param = $request->input('param', 'day');  // Default to 'day' if param is not provided

        // Prepare date ranges based on the requested param (day or week)
        if ($param == 'day') {
            // Get the start and end dates for the current day
            $currentStart = $selectedDate->copy()->startOfDay(); // Start of the current day (00:00:00)
            $currentEnd = $selectedDate->copy()->endOfDay();   // End of the current day (23:59:59)

            $startCurrent = $currentStart->toDateTimeString();  // Format: Y-m-d H:i:s
            $endCurrent = $currentEnd->toDateTimeString();      // Format: Y-m-d H:i:s

            // Get the start and end dates for the previous day
            $previousStart = $selectedDate->copy()->subDay()->startOfDay(); // Start of the previous day (00:00:00)
            $previousEnd = $selectedDate->copy()->subDay()->endOfDay();   // End of the previous day (23:59:59)

            $startPrevious = $previousStart->toDateTimeString(); // Format: Y-m-d H:i:s
            $endPrevious = $previousEnd->toDateTimeString();     // Format: Y-m-d
        } elseif ($param == 'week') {
            // Get the start and end dates for the current week
            $currentStart = $selectedDate->copy()->startOfWeek(); // Start of the current week
            $currentEnd = $selectedDate->copy()->endOfWeek();   // End of the current week

            $startCurrent = $currentStart->toDateString();  // Format: Y-m-d
            $endCurrent = $currentEnd->toDateString();      // Format: Y-m-d

            // Get the start and end dates for the previous week
            $previousStart = $selectedDate->copy()->subWeek()->startOfWeek(); // Start of the previous week
            $previousEnd = $selectedDate->copy()->subWeek()->endOfWeek();   // End of the previous week

            $startPrevious = $previousStart->toDateString(); // Format: Y-m-d
            $endPrevious = $previousEnd->toDateString();     // Format: Y-m-d
            // dd($currentStart, $currentEnd, $previousStart, $previousEnd);
        } elseif ($param == 'month') {
            $currentStart = $selectedDate->copy()->startOfMonth();
            $currentEnd = $selectedDate->copy()->endOfMonth();

            // Get the start and end dates for the previous month
            $previousStart = $selectedDate->copy()->subMonth()->startOfMonth();
            $previousEnd = $selectedDate->copy()->subMonth()->endOfMonth();

            $startCurrent = $currentStart->toDateString(); // Format: Y-m-d
            $endCurrent = $currentEnd->toDateString();   // Format: Y-m-d

            $startPrevious = $previousStart->toDateString(); // Format: Y-m-d
            $endPrevious = $previousEnd->toDateString();   // Ending date
        } else {
            // Return error if param is invalid
            return ServiceResponse::error('Invalid param value', 400);
        }

        // dd($param, $startCurrent, $endCurrent, $startPrevious, $endPrevious);
        $currentOrderProducts = OrderProduct::whereHas('order', function ($query) use ($startCurrent, $endCurrent) {
            $query->whereBetween('order_at', [$startCurrent, $endCurrent]);
        })
            ->join('products', 'order_products.product_id', '=', 'products.id') // Join with products table
            ->selectRaw('
            order_products.order_id, 
            order_products.product_id, 
            GROUP_CONCAT(order_products.quantity) as total_quantity, 
            SUM(order_products.quantity * order_products.price) as total_price, 
            products.name as category
        ')
            ->groupBy('order_products.order_id', 'order_products.product_id', 'products.name') // Group by required fields
            ->get();

        if ($currentOrderProducts->isEmpty()) {
            return ServiceResponse::success('No sales data available for the selected period.', [
                'categories' => [],
                'series' => [
                    ['name' => "This {$param}", 'data' => []],
                    ['name' => "Last {$param}", 'data' => []],
                ],
            ]);
        }
        // Transforming the data to get categories as an array
        $currentProducts = [];
        foreach ($currentOrderProducts as $orderProduct) {
            // Explode product names into an array
            $categories = explode(',', $orderProduct->category);
            if ($orderProduct->total_price > 0) {
                // dd($categories);
                $currentProducts[] = [
                    'product_name' => $categories,
                    'current_total_price' => $orderProduct->total_price,


                ];
            }
        }

        // return $currentProducts;

        $previousOrderProducts = OrderProduct::whereHas('order', function ($query) use ($startPrevious, $endPrevious) {
            $query->whereBetween('order_at', [$startPrevious, $endPrevious]);
        })
            ->join('products', 'order_products.product_id', '=', 'products.id') // Join with products table
            ->selectRaw('
            order_products.order_id, 
            order_products.product_id, 
            GROUP_CONCAT(order_products.quantity) as total_quantity, 
            SUM(order_products.quantity * order_products.price) as total_price, 
            products.name as category
            ')
            ->groupBy('order_products.order_id', 'order_products.product_id', 'products.name') // Group by required fields
            ->get();
        if ($previousOrderProducts->isEmpty()) {
            return ServiceResponse::success('No sales data available for the selected period.', [
                'categories' => [],
                'series' => [
                    ['name' => "This {$param}", 'data' => []],
                    ['name' => "Last {$param}", 'data' => []],
                ],
            ]);
        }
        // Debugging the order products and category values

        // Transforming the data to get categories as an array
        $previousProducts = [];
        foreach ($previousOrderProducts as $orderProduct) {
            // Explode product names into an array
            // dd($previousOrderProducts);
            $categories = explode(',', $orderProduct->category);
            if ($orderProduct->total_price > 0) {
                $previousProducts[] = [
                    'product_name' => $categories,
                    'previous_total_price' =>  $orderProduct->total_price,
                ];;
            }
        }

        $mergedProducts = array_merge($currentProducts, $previousProducts);
        if ($param == 'day') {

            $startDuration = $currentStart->format('Y-m-d');
            $endDuraton = $previousStart->format('Y-m-d');
        } elseif ($param == 'week') {
            $startDuration = $currentStart->week();
            $endDuraton = $previousStart->week();
        } elseif ($param == 'month') {
            $startDuration = $currentStart->format('F');
            $endDuraton = $previousStart->format('F');
        }

        $currentPrices = [];
        $previousPrices = [];

        foreach ($currentProducts as $product) {
            $currentPrices[] = $product['current_total_price'];
        }

        foreach ($previousProducts as $product) {
            $previousPrices[] = $product['previous_total_price'];
        }
        foreach ($currentProducts as $key => $product) {
            $productName = $product['product_name'][0] ?? 'Unknown';

            $categories[$productName] = [
                'current_total_price' => $product['current_total_price'],
                'previous_total_price' => $previousProducts[$key]['previous_total_price'] ?? 0,
            ];
        }
        $topCategories = array_reverse($categories) ?? [];

        // Initialize the series data structure
        $seriesData = [
            "This {$param}" => [],
            "Last {$param}" => [],
        ];

        // Fill the series data for "This Month"
        foreach ($topCategories as $category => $prices) {
            if (!is_array($prices)) {
                // If $prices is not an array, skip or log the issue
                continue; // Skip this category
            }

            // $currentPrice = $this->formatPrice($prices['current_total_price'] ?? 0);
            // $previousPrice = $this->formatPrice($prices['previous_total_price'] ?? 0);
            $currentPrice = $prices['current_total_price'] ?? 0;
            $previousPrice = $prices['previous_total_price'] ?? 0;

            $seriesData["This {$param}"][] = $currentPrice;
            $seriesData["Last {$param}"][] = $previousPrice;
        }

        $categoryTotals = array_filter($topCategories, function ($key) {
            return is_string($key);  // Only keep categories that are strings
        }, ARRAY_FILTER_USE_KEY);

        // If you want to limit to top 10 after filtering
        $categoryTotals = array_slice($categoryTotals, 0, 10, true);

        // dd($categoryTotals);

        // Prepare the series data for top 10 categories
        $seriesData = [
            "This {$param}" => [],
            "Last {$param}" => []
        ];

        // Loop through category totals and fill the series data
        foreach ($categoryTotals as $category => $totalPrice) {
            // Check if $totalPrice is an array and contains the keys you expect
            if (is_array($totalPrice)) {
                // Add the current and previous total prices to the series data
                // $seriesData["This {$param}"][] = $this->formatPrice($totalPrice['current_total_price']);
                // $seriesData["Last {$param}"][] = $this->formatPrice($totalPrice['previous_total_price']);
                $seriesData["This {$param}"][] = $totalPrice['current_total_price'];
                $seriesData["Last {$param}"][] = $totalPrice['previous_total_price'];
            }
        }

        // Prepare the response data
        $responseData = [
            'categories' => array_keys($categoryTotals),  // Extract top 10 category names
            'series' => [
                [
                    'name' => "This {$param}",
                    'data' => $seriesData["This {$param}"],
                ],
                [
                    'name' => "Last {$param}",
                    'data' => $seriesData["Last {$param}"],
                ],
            ],
        ];


        return ServiceResponse::success('Sales Chart Data', $responseData);
    }

    /**
     * Format the price into k, M, and B notation
     * 
     * @param float $price
     * @return string
     */
    // private function formatPrice($price)
    // {
    //     if ($price >= 1000000000) {
    //         return number_format($price / 1000000000) . 'B';  // No decimal for billions
    //     } elseif ($price >= 1000000) {
    //         return number_format($price / 1000000) . 'M';  // No decimal for millions
    //     } elseif ($price >= 1000) {
    //         return number_format($price / 1000) . 'K';  // No decimal for thousands
    //     }

    //     return number_format($price);  // No decimal for values less than 1000
    // }
}
