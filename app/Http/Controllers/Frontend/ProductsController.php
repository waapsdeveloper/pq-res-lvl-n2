<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function popdishes(Request $request)
    {
        // Set default pagination parameters
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);

        // Query to fetch products
        $query = Product::query()->limit(8);
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Transform the collection into the desired format
        $data->getCollection()->transform(function ($product) {
            return [
                "id" => $product->id,
                "category" => $product->category,
                "name" => $product->name,
                "price" => $product->price,
                "image" => Helper::returnFullImageUrl($product->image),
                "status" => $product->status,
            ];
        });

        // Return the transformed data
        return ServiceResponse::success('Popular dishes available', ['products' => $data]);
    }

    /**
     * Helper function to return full image URL.
     */
}
