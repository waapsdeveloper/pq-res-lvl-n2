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
use DateTime;
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
        // Fetch top 5 selling products with their quantities
        $topSellingProducts = OrderProduct::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // Calculate the total quantity of all top-selling products
        $totalQuantity = $topSellingProducts->sum('total_quantity');

        // Prepare data for the pie chart
        $productLabels = [];
        $productPercentages = [];

        foreach ($topSellingProducts as $product) {
            // Fetch product name or use product_id if name isn't available
            $productName = DB::table('products')->where('id', $product->product_id)->value('name') ?? "Product {$product->product_id}";

            // Calculate the percentage for each product
            $percentage = $totalQuantity > 0 ? ($product->total_quantity / $totalQuantity) * 100 : 0;

            $productLabels[] = $productName;
            $productPercentages[] = round($percentage, 2); // Round to 2 decimal places
        }

        // Pie chart data
        $chartOptions = [
            'series' => $productPercentages,
            'labels' => $productLabels,
        ];

        return ServiceResponse::success('Products sorted by total quantity', ['order_products' => $chartOptions]);
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

    // public function customerChartData(Request $request)
    // {
    //     $param = $request->input('param', 'day');

    //     // Initialize customer data arrays
    //     $newCustomerCount = 0;
    //     $returningCustomerCount = 0;
    //     $totalCustomers = 0;

    //     $currentMonth = Carbon::now()->format('F'); // Current month name
    //     $previousMonth = Carbon::now()->subMonth()->format('F'); // Previous month name

    //     // Fetch customer data based on param ('day' or 'week')
    //     if ($param == 'day') {
    //         // New customers count for today
    //         $newCustomers = DB::select("
    //             SELECT customer_id 
    //             FROM orders 
    //             WHERE DATE(created_at) = CURDATE()
    //             GROUP BY customer_id
    //             HAVING COUNT(*) = 1
    //         ");
    //         $newCustomerCount = count($newCustomers);

    //         // Returning customers count for today
    //         $returningCustomers = DB::select("
    //             SELECT customer_id 
    //             FROM orders 
    //             WHERE DATE(created_at) = CURDATE()
    //             GROUP BY customer_id
    //             HAVING COUNT(*) > 1
    //         ");
    //         $returningCustomerCount = count($returningCustomers);

    //         // Total unique customers count for today
    //         $totalCustomersData = DB::select("
    //             SELECT DISTINCT customer_id
    //             FROM orders
    //             WHERE DATE(created_at) = CURDATE()
    //         ");
    //         $totalCustomers = count($totalCustomersData);
    //     } elseif ($param == 'week') {
    //         // New customers count for this week
    //         $newCustomers = DB::select("
    //             SELECT customer_id 
    //             FROM orders 
    //             WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)
    //             GROUP BY customer_id
    //             HAVING COUNT(*) = 1
    //         ");
    //         $newCustomerCount = count($newCustomers);

    //         // Returning customers count for this week
    //         $returningCustomers = DB::select("
    //             SELECT customer_id 
    //             FROM orders 
    //             WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)
    //             GROUP BY customer_id
    //             HAVING COUNT(*) > 1
    //         ");
    //         $returningCustomerCount = count($returningCustomers);

    //         // Total unique customers count for this week
    //         $totalCustomersData = DB::select("
    //             SELECT DISTINCT customer_id
    //             FROM orders
    //             WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)
    //         ");
    //         $totalCustomers = count($totalCustomersData);
    //     }
    //     $param = ucfirst($param);
    //     // Prepare dynamic response
    //     $response = [
    //         'series' => [
    //             [
    //                 'name' => 'New Customers',
    //                 'data' => [$newCustomerCount]
    //             ],
    //             [
    //                 'name' => 'Returning Customers',
    //                 'data' => [$returningCustomerCount]
    //             ],
    //             [
    //                 'name' => "Total Customers in {$param}",
    //                 'data' => [$totalCustomers]
    //             ]
    //         ],

    //         'xaxis' => [
    //             'categories' => $param == 'day' ? ['Today', 'Yesterday'] : [$currentMonth, $previousMonth]
    //         ]
    //     ];

    //     return ServiceResponse::success('Customer Chart data fetched successfully', ['customers' => $response]);
    // }



    public function customerChartData(Request $request)
    {
        $param = $request->input('param', 'day');

        // Initialize customer data arrays
        $newCustomerCount = [];
        $returningCustomerCount = [];
        $totalCustomers = [];

        // Current and previous months for 'week' param
        $currentMonth = Carbon::now()->format('F');
        $previousMonth = Carbon::now()->subMonth()->format('F');

        if ($param == 'day') {
            // Loop to get data for the last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');

                // New customers count for each day
                $newCustomers = DB::select("
                SELECT customer_id 
                FROM orders 
                WHERE DATE(created_at) = ?
                GROUP BY customer_id
                HAVING COUNT(*) = 1
            ", [$date]);
                $newCustomerCount[] = count($newCustomers);

                // Returning customers count for each day
                $returningCustomers = DB::select("
                SELECT customer_id 
                FROM orders 
                WHERE DATE(created_at) = ?
                GROUP BY customer_id
                HAVING COUNT(*) > 1
            ", [$date]);
                $returningCustomerCount[] = count($returningCustomers);

                // Total unique customers count for each day
                $totalCustomersData = DB::select("
                SELECT DISTINCT customer_id
                FROM orders
                WHERE DATE(created_at) = ?
            ", [$date]);
                $totalCustomers[] = count($totalCustomersData);
            }

            // Prepare dynamic response for last 7 days
            $response = [
                'series' => [
                    [
                        'name' => 'New Customers',
                        'data' => array_reverse($newCustomerCount)
                    ],
                    [
                        'name' => 'Returning Customers',
                        'data' => array_reverse($returningCustomerCount)
                    ],
                    [
                        'name' => "Total Customers in {$param}",
                        'data' => array_reverse($totalCustomers)
                    ]
                ],
                'xaxis' => [
                    'categories' => [
                        Carbon::now()->format('D'),
                        Carbon::now()->subDays(1)->format('D'),
                        Carbon::now()->subDays(2)->format('D'),
                        Carbon::now()->subDays(3)->format('D'),
                        Carbon::now()->subDays(4)->format('D'),
                        Carbon::now()->subDays(5)->format('D'),
                        Carbon::now()->subDays(6)->format('D'),
                    ]
                ]
            ];
        } elseif ($param == 'week') {
            // Loop to get data for the last 7 weeks
            for ($i = 6; $i >= 0; $i--) {
                $week = Carbon::now()->subWeeks($i)->format('W');
                $year = Carbon::now()->subWeeks($i)->format('Y');

                // New customers count for each week
                $newCustomers = DB::select("
                SELECT customer_id 
                FROM orders 
                WHERE YEARWEEK(created_at, 1) = YEARWEEK(?, 1)
                GROUP BY customer_id
                HAVING COUNT(*) = 1
            ", [Carbon::now()->subWeeks($i)->startOfWeek()]);
                $newCustomerCount[] = count($newCustomers);

                // Returning customers count for each week
                $returningCustomers = DB::select("
                SELECT customer_id 
                FROM orders 
                WHERE YEARWEEK(created_at, 1) = YEARWEEK(?, 1)
                GROUP BY customer_id
                HAVING COUNT(*) > 1
            ", [Carbon::now()->subWeeks($i)->startOfWeek()]);
                $returningCustomerCount[] = count($returningCustomers);

                // Total unique customers count for each week
                $totalCustomersData = DB::select("
                SELECT DISTINCT customer_id
                FROM orders
                WHERE YEARWEEK(created_at, 1) = YEARWEEK(?, 1)
            ", [Carbon::now()->subWeeks($i)->startOfWeek()]);
                $totalCustomers[] = count($totalCustomersData);
            }

            // Prepare dynamic response for last 7 weeks
            $response = [
                'series' => [
                    [
                        'name' => 'New Customers',
                        'data' => array_reverse($newCustomerCount)
                    ],
                    [
                        'name' => 'Returning Customers',
                        'data' => array_reverse($returningCustomerCount)
                    ],
                    [
                        'name' => "Total Customers in {$param}",
                        'data' => array_reverse($totalCustomers)
                    ]
                ],
                'xaxis' => [
                    'categories' => [
                        'Week 1',
                        'Week 2',
                        'Week 3',
                        'Week 4',
                        'Week 5',
                        'Week 6',
                        'Week 7',
                    ]
                ]
            ];
        }

        return ServiceResponse::success('Customer Chart data fetched successfully', ['customers' => $response]);
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

            $currentPrice = $this->formatPrice($prices['current_total_price'] ?? 0);
            $previousPrice = $this->formatPrice($prices['previous_total_price'] ?? 0);

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
