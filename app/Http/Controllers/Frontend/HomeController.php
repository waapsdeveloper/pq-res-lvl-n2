<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Resources\Frontend\PopularProductsResource;
use App\Models\BranchConfig;
use App\Models\Rtable;
use App\Models\RestaurantMeta;

class HomeController extends Controller
{
    public function roles()
    {
        $roles = Role::get();
        return ServiceResponse::success('roles are retrived successfully', ['data' => $roles]);
    }
    public function restautantDetail($id)
    {
        $restuarant = Restaurant::with('timings', 'rTables')->findOrFail($id);
        $restuarant->image = Helper::returnFullImageUrl($restuarant->image);
        $restuarant->logo = Helper::returnFullImageUrl($restuarant->logo);
        $restuarant->favicon = Helper::returnFullImageUrl($restuarant->favicon);

        return ServiceResponse::success('Restaurant are retrived successfully', ['data' => $restuarant]);
    }
    public function showActiveRestaurant()
    {
        $activeRestaurant = Helper::getActiveRestaurantId();
        return ServiceResponse::success('Active Restaurant ID', ['active_restaurant' => $activeRestaurant]);
    }

    public function getRestaurantConfigById(Request $request, $id)
    {
        $restaurant = Restaurant::where('id', $id)->first();
        if(!$restaurant) {
            return ServiceResponse::error('Restaurant not found', [], 404);
        }

        $config = BranchConfig::where('branch_id', $id)->first();
        if (!$config) {
            // If no config exists, create a default one
            $config = BranchConfig::create([
                'branch_id' => $id,
                'currency' => 'USD', // Default currency, can be changed later
                'tax' => 0, // Default tax, can be changed later
                'dial_code' => '+1', // Default dial code, can be changed later
                'currency_symbol' => '$', // Default currency symbol, can be changed later
            ]);
        }



        return ServiceResponse::success('Restaurant config retrieved successfully', ['data' => $config]);

    }

    public function getRestaurantMeta($id)
    {
        $restaurant = Restaurant::find($id);
        if(!$restaurant) {
            return ServiceResponse::error('Restaurant not found', [], 404);
        }

        // Get all meta data for the restaurant
        $metaData = RestaurantMeta::where('restaurant_id', $id)->get();
        
        // Transform meta data into key-value pairs
        $meta = [];
        foreach ($metaData as $metaItem) {
            $meta[$metaItem->meta_key] = $metaItem->meta_value;
        }

        return ServiceResponse::success('Restaurant meta data retrieved successfully', [
            'restaurant_id' => $id,
            'meta' => $meta
        ]);
    }

    public function getRestaurantWithMeta($id)
    {
        $restaurant = Restaurant::with('timings', 'meta')->find($id);
        if(!$restaurant) {
            return ServiceResponse::error('Restaurant not found', [], 404);
        }

        // Transform meta data into key-value pairs
        $meta = [];
        if ($restaurant->meta && $restaurant->meta->count() > 0) {
            foreach ($restaurant->meta as $metaItem) {
                $meta[$metaItem->meta_key] = $metaItem->meta_value;
            }
        }

        // Prepare restaurant data
        $restaurantData = [
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'address' => $restaurant->address,
            'phone' => $restaurant->phone,
            'email' => $restaurant->email,
            'website' => $restaurant->website,
            'description' => $restaurant->description,
            'rating' => $restaurant->rating,
            'status' => $restaurant->status,
            'image' => Helper::returnFullImageUrl($restaurant->image),
            'logo' => Helper::returnFullImageUrl($restaurant->logo),
            'favicon' => Helper::returnFullImageUrl($restaurant->favicon),
            'copyright_text' => $restaurant->copyright_text,
            'is_active' => $restaurant->is_active,
            'meta' => $meta,
            'schedule' => $restaurant->timings->map(function ($item) {
                return [
                    'day' => $item->day,
                    'start_time' => $item->start_time,
                    'end_time' => $item->end_time,
                    'status' => $item->status,
                ];
            }),
        ];

        return ServiceResponse::success('Restaurant with meta data retrieved successfully', [
            'restaurant' => $restaurantData
        ]);
    }

    public function aboutUs(Request $request)
    {
        $categories = Category::withCount('products')
            ->where('restaurant_id', (int) $request->restaurant_id)
            ->get();

        $categories = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'image' => Helper::returnFullImageUrl($category->image),
                'products_count' => $category->products_count,
            ];
        });

        return ServiceResponse::success('Categories retrieved successfully', ['data' => $categories]);
    }

    public function lowestPrice(Request $request)
    {
        $products = Product::orderBy('price', 'asc')
            ->where('restaurant_id', (int) $request->restaurant_id)
            ->first();
        return ServiceResponse::success('Lowest Price', ['products' => $products]);
    }
    public function allBranches()
    {
        $allBranches = Restaurant::get();

        $data = $allBranches->transform(function ($branch) {
            $branch->image = Helper::returnFullImageUrl($branch->image);
            $branch->logo = Helper::returnFullImageUrl($branch->logo);
            $branch->favicon = Helper::returnFullImageUrl($branch->favicon);
            return $branch;
        });

        return ServiceResponse::success('allBranches are retrived successfully', ['data' => $data]);
    }
    public function getPopularProducts(Request $request)
    {
        // Set default pagination parameters
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 8);
        // Query to fetch products
        $query = Product::query()
            ->with('category', 'restaurant', 'productProps', 'variation')

            ->where('restaurant_id', (int) $request->restaurant_id)
            ->limit(8);
        $data = $query->paginate($perpage, ['*'], 'page', $page);
        // Transform the collection into the desired format
        $data->getCollection()->transform(function ($product) {
            // return new PopularProductsResource($product);
            return new ProductResource($product);
        });

        return ServiceResponse::success('Popular dishes available', ['products' => $data]);
    }
    public function todayDeals(Request $request)
    {
        $deals = [];
        // Loop until we have 5 deals
        while (count($deals) < 5) {
            // Get 3 random categories
            $categories = Category::query()
                ->where('restaurant_id', (int) $request->restaurant_id)
                ->where('status', 'active')->inRandomOrder()->limit(2)->get();

            $products = [];
            $totalPrice = 0;

            // For each category, get 1 random product
            foreach ($categories as $category) {
                // Get 1 random product for each category
                $product = Product::where('category_id', $category->id)
                    ->where('status', 'active')
                    ->inRandomOrder()
                    ->first();

                if ($product) {
                    $products[] = $product;
                    $totalPrice += $product->price;
                }
            }

            // If we successfully got 3 products, calculate the discounted price
            if (count($products) == 3) {
                $discountedPrice = $totalPrice * 0.90; // Apply 10% discount

                // Add the deal to the list
                $deals[] = [
                    'products' => $products,
                    'total_price' => $totalPrice,
                    'discounted_price' => $discountedPrice
                ];
            }
        }
        return ServiceResponse::success("Today's deals fetched successfully", $deals);
    }
    public function getByRestaurantId(string $id)
    {
        $restaurants = Rtable::with('restaurant:id,name')
            ->where('restaurant_id', $id)
            ->select('id', 'restaurant_id', 'floor', 'identifier', 'no_of_seats')
            ->withCount('orders')
            ->get()
            ->groupBy('restaurant_id');

        $restaurantData = $restaurants->first();

        $floors = $restaurantData->pluck('floor')->unique()->values()->toArray();

        return ServiceResponse::success('Tables fetched for you', [
            'restaurant' => $restaurants->first(),
            'floors' => $floors
        ]);
    }

    /**
     * Get grouped opening hours for a restaurant
     */
    public function getOpeningHours($id)
    {
        $restaurantId = $id;
        $timingConfig = \App\Models\RestaurantTiming::getTimingConfig($restaurantId);
        $days = \App\Models\RestaurantTiming::getDayOptions();

        // Build an array of [day, formatted_timing]
        $dayTimings = [];
        foreach ($days as $dayKey => $dayName) {
            $timing = \App\Models\RestaurantTiming::getFormattedTiming($restaurantId, $dayKey);
            $dayTimings[] = [
                'day' => $dayName,
                'timing' => $timing
            ];
        }

        // Group consecutive days with the same timing
        $grouped = [];
        $currentGroup = null;
        foreach ($dayTimings as $i => $item) {
            if ($currentGroup === null) {
                $currentGroup = [
                    'days' => [$item['day']],
                    'timing' => $item['timing']
                ];
            } else if ($item['timing'] === $currentGroup['timing']) {
                $currentGroup['days'][] = $item['day'];
            } else {
                $grouped[] = $currentGroup;
                $currentGroup = [
                    'days' => [$item['day']],
                    'timing' => $item['timing']
                ];
            }
        }
        if ($currentGroup !== null) {
            $grouped[] = $currentGroup;
        }

        // Format the grouped data into readable strings
        $formattedHours = [];
        foreach ($grouped as $group) {
            $daysCount = count($group['days']);
            
            if ($daysCount === 1) {
                // Single day
                $formattedHours[] = $group['days'][0] . ': ' . $group['timing'];
            } else {
                // Multiple consecutive days
                $firstDay = $group['days'][0];
                $lastDay = $group['days'][$daysCount - 1];
                $formattedHours[] = $firstDay . ' to ' . $lastDay . ': ' . $group['timing'];
            }
        }

        return \App\Helpers\ServiceResponse::success('Opening hours retrieved successfully', [
            'opening_hours' => $formattedHours,
            'raw_data' => $grouped // Keep the raw data for backward compatibility
        ]);
    }

    /**
     * Get formatted opening hours for a restaurant (formatted as requested)
     */
    public function getFormattedOpeningHours($id)
    {
        $restaurantId = $id;
        $days = \App\Models\RestaurantTiming::getDayOptions();

        // Build an array of [day, formatted_timing]
        $dayTimings = [];
        foreach ($days as $dayKey => $dayName) {
            $timing = \App\Models\RestaurantTiming::getFormattedTiming($restaurantId, $dayKey);
            $dayTimings[] = [
                'day' => $dayName,
                'timing' => $timing
            ];
        }

        // Group consecutive days with the same timing
        $grouped = [];
        $currentGroup = null;
        foreach ($dayTimings as $i => $item) {
            if ($currentGroup === null) {
                $currentGroup = [
                    'days' => [$item['day']],
                    'timing' => $item['timing']
                ];
            } else if ($item['timing'] === $currentGroup['timing']) {
                $currentGroup['days'][] = $item['day'];
            } else {
                $grouped[] = $currentGroup;
                $currentGroup = [
                    'days' => [$item['day']],
                    'timing' => $item['timing']
                ];
            }
        }
        if ($currentGroup !== null) {
            $grouped[] = $currentGroup;
        }

        // Format the grouped data into readable strings exactly as requested
        $formattedHours = [];
        foreach ($grouped as $group) {
            $daysCount = count($group['days']);
            
            if ($daysCount === 1) {
                // Single day
                $formattedHours[] = $group['days'][0] . "\n" . $group['timing'];
            } else {
                // Multiple consecutive days
                $firstDay = $group['days'][0];
                $lastDay = $group['days'][$daysCount - 1];
                $formattedHours[] = $firstDay . ' to ' . $lastDay . "\n" . $group['timing'];
            }
        }

        return \App\Helpers\ServiceResponse::success('Formatted opening hours retrieved successfully', [
            'formatted_opening_hours' => $formattedHours
        ]);
    }
}
