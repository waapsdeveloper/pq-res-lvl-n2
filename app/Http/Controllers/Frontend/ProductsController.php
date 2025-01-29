<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\FrontendMenuResource;
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
            return new FrontendMenuResource($category);
        });

        return ServiceResponse::success('Popular dishes available', ['products' => $data]);
    }
    public function productByCategory($id)
    {
        $query = Product::where('category_id', $id);
        $data = $query->paginate(9);
        // Transform the collection into the desired format
        $data->getCollection()->transform(function ($product) {
            return new ProductResource($product);
        });

        // Return the transformed data with a success message
        return ServiceResponse::success('Products retrieved by category', ['products' => $data]);
    }

    

    public function getByCategory($id)
    {
        // Query to fetch products by category_id
        $query = Product::where('category_id', $id);

        // Paginate the results
        $data = $query->paginate(12);
        // Transform the collection into the desired format
        $data->getCollection()->transform(function ($product) {
            return new ProductResource($product);
        });

        // Return the transformed data with a success message
        return ServiceResponse::success('Products retrieved by category', ['products' => $data]);
    }
}
