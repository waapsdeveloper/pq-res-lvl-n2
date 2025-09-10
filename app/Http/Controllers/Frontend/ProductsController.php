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
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 20);

        $query = Product::query()
            ->where('restaurant_id', (int) $request->restaurant_id)
            ->whereIn('status', ['Active', 'active'])
            ->whereHas('category', function ($q) {
                $q->whereIn('status', ['Active', 'active']);
            })
            ->with('category', 'productProps', 'variation');

        // If category_id is provided, check if the category is active
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

        // Query to fetch products
        $query = Category::query()
            ->where('restaurant_id', (int) $request->restaurant_id)

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
        // Only fetch products whose category is active
        $query = Product::where('category_id', $id)
            ->whereIn('status', ['Active', 'active'])
            ->whereHas('category', function ($q) {
                $q->whereIn('status', ['Active', 'active']);
            });

        $data = $query->paginate(9);
        $data->getCollection()->transform(function ($product) {
            return new ProductResource($product);
        });

        return ServiceResponse::success('Products retrieved by category', ['products' => $data]);
    }



    public function getByCategory($id)
    {
        // Only fetch products whose category is active
        $query = Product::where('category_id', $id)
            ->whereIn('status', ['Active', 'active'])
            ->whereHas('category', function ($q) {
                $q->whereIn('status', ['Active', 'active']);
            });

        $data = $query->paginate(12);
        $data->getCollection()->transform(function ($product) {
            return new ProductResource($product);
        });

        return ServiceResponse::success('Products retrieved by category', ['products' => $data]);
    }
}
