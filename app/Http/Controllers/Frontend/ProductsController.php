<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function popdishes(Request $request)
    {
        // Set default pagination parameters
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 8);

        // Query to fetch products
        $query = Product::query()->limit(8);
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

        return ServiceResponse::success('Popular dishes available', ['products' => $data]);
    }
    public function menu(Request $request)
    {
        // Set default pagination parameters
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 8);

        // Query to fetch products
        $query = Category::query()->limit(8);
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

        // Query to fetch products by category_id
        $query = Product::where('category_id', $id);

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
}
