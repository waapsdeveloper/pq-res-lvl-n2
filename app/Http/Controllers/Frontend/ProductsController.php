<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function popdishes()
    {
        $products = Product::limit(8)->get();
        return ServiceResponse::success('Popular dishes available', ['products' => $products]);
    }
}
