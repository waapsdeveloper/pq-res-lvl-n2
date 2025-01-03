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


    // public function getSalesChartData(Request $request)
    // {
    //     $thisDate = $request->input('date', Carbon::now()->toDateString());
    //     $lastDate = Carbon::parse($thisDate)->subDay()->toDateString();  // Use dynamic date for last day

    //     // Sum product prices for this day (without quantity)
    //     $thisDayData = DB::table('order_products')
    //         ->join('products', 'order_products.product_id', '=', 'products.id')
    //         ->select('order_products.product_id', 'products.name as category', DB::raw('SUM(order_products.quantity * products.price) as total_price'))
    //         ->whereDate('order_products.created_at', $thisDate)
    //         ->groupBy('order_products.product_id', 'products.name')
    //         ->orderByDesc('total_price')  // Sort by total price in descending order
    //         ->get()
    //         ->take(10);  // Limit to the top 10

    //     // Sum product prices for the last day (without quantity)
    //     $lastDayData = DB::table('order_products')
    //         ->join('products', 'order_products.product_id', '=', 'products.id')
    //         ->select('order_products.product_id', 'products.name as category', DB::raw('SUM(order_products.quantity * products.price) as total_price'))
    //         ->whereDate('order_products.created_at', $lastDate)
    //         ->groupBy('order_products.product_id', 'products.name')
    //         ->orderByDesc('total_price')  // Sort by total price in descending order
    //         ->get()
    //         ->take(10);  // Limit to the top 10

    //     // Merge categories from both days, ensuring uniqueness
    //     $categories = $thisDayData->pluck('category')
    //         ->merge($lastDayData->pluck('category'))
    //         ->unique()
    //         ->values()
    //         ->all();

    //     // Initialize the series data structure
    //     $seriesData = [
    //         'This Day' => array_fill(0, count($categories), 0),  // Default to 0 for this day
    //         'Last Day' => array_fill(0, count($categories), 0),  // Default to 0 for last day
    //     ];

    //     // Map product prices to the correct categories (index-based)
    //     $categoryIndex = array_flip($categories);  // Map category names to their index positions

    //     // Add this day data to series data
    //     foreach ($thisDayData as $item) {
    //         $index = $categoryIndex[$item->category];
    //         $seriesData['This Day'][$index] = $this->formatPrice($item->total_price);
    //     }

    //     // Add last day data to series data
    //     foreach ($lastDayData as $item) {
    //         $index = $categoryIndex[$item->category];
    //         $seriesData['Last Day'][$index] = $this->formatPrice($item->total_price);
    //     }

    //     // Filter out products where both 'This Day' and 'Last Day' total prices are zero
    //     $filteredData = [];
    //     foreach ($categoryIndex as $category => $index) {
    //         if ($seriesData['This Day'][$index] > 0 || $seriesData['Last Day'][$index] > 0) {
    //             $filteredData[] = [
    //                 'category' => $category,
    //                 'this_day_total' => $seriesData['This Day'][$index],
    //                 'last_day_total' => $seriesData['Last Day'][$index]
    //             ];
    //         }
    //     }

    //     // Take only the first 10 items for both days (after filtering)
    //     $filteredData = array_slice($filteredData, 0, 10);

    //     // Prepare the final series data
    //     $finalCategories = array_column($filteredData, 'category');
    //     $thisDayTotals = array_column($filteredData, 'this_day_total');
    //     $lastDayTotals = array_column($filteredData, 'last_day_total');

    //     $responseData = [
    //         'categories' => $finalCategories,
    //         'series' => [
    //             [
    //                 'name' => 'This Day',
    //                 'data' => $thisDayTotals
    //             ],
    //             [
    //                 'name' => 'Last Day',
    //                 'data' => $lastDayTotals
    //             ]
    //         ]
    //     ];

    //     return ServiceResponse::success('Sales Chart Data', $responseData);
    // }

    // /**
    //  * Format the price into k, M, and B notation
    //  * 
    //  * @param float $price
    //  * @return string
    //  */
    // private function formatPrice($price)
    // {
    //     if ($price >= 1000000000) {
    //         return number_format($price / 1000000000, 1) . 'B';
    //     } elseif ($price >= 1000000) {
    //         return number_format($price / 1000000, 1) . 'M';
    //     } elseif ($price >= 1000) {
    //         return number_format($price / 1000, 1) . 'K';
    //     }

    //     return number_format($price, 2);  // Format for values less than 1000
    // }

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

        // Transforming the data to get categories as an array
        $currentProducts = [];
        foreach ($currentOrderProducts as $orderProduct) {
            // Explode product names into an array
            $categories = explode(',', $orderProduct->category);
            if ($orderProduct->total_price > 0) {
                // dd($categories);
                $currentProducts[] = [
                    'product_name' => $categories,
                    'total_price' => $orderProduct->total_price
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

        // Transforming the data to get categories as an array
        $previousProducts = [];
        foreach ($previousOrderProducts as $orderProduct) {
            // Explode product names into an array
            // dd($previousOrderProducts);
            $categories = explode(',', $orderProduct->category);
            if ($orderProduct->total_price > 0) {
                $previousProducts[] = [
                    'product_name' => $categories,
                    'total_price' => $orderProduct->total_price
                ];
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
        // $currentMonth  = $currentMonthStart->format('F');
        // $previousMonth = $previousMonthStart->format('F');
        $categories = [];
        foreach ($mergedProducts as $product) {
            foreach ($product['product_name'] as $category) {
                if (!isset($categories[$category])) {
                    $categories[$category] = [];
                }

                // Store products by category and month
                $categories[$category][] = [
                    'product_name' => $category,
                    "{$param}_current" => $startDuration ? $startDuration : null,
                    "{$param}_previous" => $endDuraton ? $endDuraton : null,
                    'total_price' => $product['total_price']
                ];
            }
        }

        // Initialize the series data structure
        $seriesData = [
            "This {$param}" => [],
            "Last {$param}" => [],
        ];

        // Fill the series data for "This Month"
        foreach ($categories as $category => $items) {
            $totalPriceThis = 0;
            foreach ($items as $item) {
                if ($item["{$param}_current"] === $startDuration) {
                    $totalPriceThis += $item['total_price'];
                }
            }

            $currentPrice = $this->formatPrice($prices['current_total_price'] ?? 0);
            $previousPrice = $this->formatPrice($prices['previous_total_price'] ?? 0);

            $seriesData["This {$param}"][] = $currentPrice;
            $seriesData["Last {$param}"][] = $previousPrice;
        }

        // Fill the series data for "Last Month"
        $categoryTotals = [];

        // Loop through categories to calculate the total price for each category
        foreach ($categories as $category => $items) {
            $totalPriceCurrent = 0;
            $totalPriceLast = 0;

            foreach ($items as $item) {
                // Add current month price
                if ($item["{$param}_current"] === $startDuration) {
                    $totalPriceCurrent += $item['total_price'];
                }

                // Add previous month price
                if ($item["{$param}_previous"] === $endDuraton) {
                    $totalPriceLast += $item['total_price'];
                }
            }

            // Store the total price for this category
            $categoryTotals[$category] = [
                "{$param}_current" => $totalPriceCurrent,
                "{$param}_previous" => $totalPriceLast
            ];
        }

        // Sort categories by total price for this month in descending order
        arsort($categoryTotals);

        // Limit to top 10 categories
        $categoryTotals = array_slice($categoryTotals, 0, 10, true);

        // Prepare the series data for top 10 categories
        $seriesData = [
            "This {$param}" => [],
            "Last {$param}" => []
        ];

        // Fill series data for top 10 categories
        foreach ($categoryTotals as $category => $totalPrice) {
            // Check if $totalPrice is an array and contains the keys you expect
            if (is_array($totalPrice)) {
                // Add the current and previous total prices to the series data
                $seriesData["This {$param}"][] = $this->formatPrice($totalPrice['current_total_price']);
                $seriesData["Last {$param}"][] = $this->formatPrice($totalPrice['previous_total_price']);
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
    private function formatPrice($price)
    {
        if ($price >= 1000000000) {
            return number_format($price / 1000000000) . 'B';  // No decimal for billions
        } elseif ($price >= 1000000) {
            return number_format($price / 1000000) . 'M';  // No decimal for millions
        } elseif ($price >= 1000) {
            return number_format($price / 1000) . 'K';  // No decimal for thousands
        }

        return number_format($price);  // No decimal for values less than 1000
    }
}
