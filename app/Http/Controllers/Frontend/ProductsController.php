<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function popdishes()
    {
        $products = Product::limit(8)->get();

        $products->getCollection()->transform(function ($item) {
            return new ProductResource($item);
        });
        return ServiceResponse::success('Popular dishes available', ['products' => $products]);
    }
}
