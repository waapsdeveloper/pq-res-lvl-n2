<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\PopularProductsResource;
use App\Http\Resources\Frontend\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function getProducts(Request $request)
    {
        // Set default pagination parameters
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 100);
        $active_restaurant = Helper::getActiveRestaurantId();
        $resID = $request->restaurant_id == -1 ? $active_restaurant->id : $request->restaurant_id;
        // Query to fetch products
        $query = Product::query()
            ->where('restaurant_id', $resID)
            ->with('category', 'productProps', 'variation');

        // if category_id
        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $data = $query->paginate($perpage, ['*'], 'page', $page);

        $data->getCollection()->transform(function ($item) {
            return new ProductResource($item);
        });

        return ServiceResponse::success('Products retrieved successfully', ['products' => $data]);
    }

    public function getPopularProducts(Request $request)
    {
        // Set default pagination parameters
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 8);
        $active_restaurant = Helper::getActiveRestaurantId();
        $resID = $request->restaurant_id == -1 ? $active_restaurant->id : $request->restaurant_id;

        // Query to fetch products
        $query = Product::query()
            ->with('category', 'restaurant', 'productProps', 'variation')

            ->where('restaurant_id', $resID)
            ->limit(8);
        $data = $query->paginate($perpage, ['*'], 'page', $page);
        // Transform the collection into the desired format
        $data->getCollection()->transform(function ($product) {
         return new PopularProductsResource($product);
        });

        return ServiceResponse::success('Popular dishes available', ['products' => $data]);
    }
    public function menu(Request $request)
    {
        // Set default pagination parameters
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 8);
        $active_restaurant = Helper::getActiveRestaurantId();
        $resID = $request->restaurant_id == -1 ? $active_restaurant->id : $request->restaurant_id;
        // Query to fetch products
        $query = Category::query()
            ->where('restaurant_id', $resID)

            ->limit(8);
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Transform the collection into the desired format
        $data->getCollection()->transform(function ($category) {
            return [
                "id" => $category->id,
                "name" => $category->name,
                'category_id' => $category->category_id,
                'restaurant_id' => $category->restaurant_id,
                'identifier' => $category->identifier,
                'description' => $category->description,
                "image" => Helper::returnFullImageUrl($category->image),
                "status" => $category->status,
            ];
        });

        return ServiceResponse::success('Popular dishes available', ['products' => $data]);
    }
    public function productByCategory(Request $request, $id)
    {
        // Set default pagination parameters
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 8);

        $active_restaurant = Helper::getActiveRestaurantId();
        $resID = $request->restaurant_id == -1 ? $active_restaurant->id : $request->restaurant_id;
        // Query to fetch products by category_id
        $query = Product::where('category_id', $id)
            ->where('restaurant_id', $resID);

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Transform the collection into the desired format
        $data->getCollection()->transform(function ($product) {
            return [
                "id" => $product->id,
                "category_id" => $product->category_id,
                "name" => $product->name,
                "price" => $product->price,
                "image" => Helper::returnFullImageUrl($product->image),
                "status" => $product->status,
            ];
        });

        // Return the transformed data with a success message
        return ServiceResponse::success('Products retrieved by category', ['products' => $data]);
    }

    public function todayDeals(Request $request)
    {
        // $page = $request->input('page', 1);
        // $perpage = $request->input('perpage', 8);
        // dd($request->all());
        $active_restaurant = Helper::getActiveRestaurantId();
        $resID = $request->restaurant_id == -1 ? $active_restaurant->id : $request->restaurant_id;
        //     $perpage = $request->input('perpage', 8);
        $deals = [];
        // Loop until we have 5 deals
        while (count($deals) < 5) {
            // Get 3 random categories
            $categories = Category::query()
                ->where('restaurant_id', $resID)
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
