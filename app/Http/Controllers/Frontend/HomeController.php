<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Resources\Frontend\PopularProductsResource;



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
        return ServiceResponse::success('Restaurant are retrived successfully', ['data' => $restuarant]);
    }
    public function showActiveRestaurant()
    {
        $activeRestaurant = Helper::getActiveRestaurantId();
        return ServiceResponse::success('Active Restaurant ID', ['active_restaurant' => $activeRestaurant]);
    }

    public function aboutUs(Request $request)
    {

        $categories = Category::withCount('products')
            ->where('restaurant_id', $request->restaurant_id)
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
            ->where('restaurant_id', $request->restaurant_id)
            ->first();
        return ServiceResponse::success('Lowest Price', ['products' => $products]);
    }
    public function restaurants()
    {
        $restaurants = Restaurant::get();
        return ServiceResponse::success('Restaurants are retrived successfully', ['data' => $restaurants]);
    }
    public function getPopularProducts(Request $request)
    {
        // Set default pagination parameters
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 8);
        // Query to fetch products
        $query = Product::query()
            ->with('category', 'restaurant', 'productProps', 'variation')

            ->where('restaurant_id', $request->restaurant_id)
            ->limit(8);
        $data = $query->paginate($perpage, ['*'], 'page', $page);
        // Transform the collection into the desired format
        $data->getCollection()->transform(function ($product) {
            return new PopularProductsResource($product);
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
                ->where('restaurant_id', $request->restaurant_id)
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
}
