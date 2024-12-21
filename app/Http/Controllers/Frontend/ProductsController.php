<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function popdishes(Request $request)
    {
        $query = Product::query()->limit(8);
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $data = $query->paginate($perpage, ['*'], 'page', $page);
        $data->getCollection()->transform(function ($item) {
            return new ProductResource($item);
        });
        return ServiceResponse::success('Popular dishes available', ['products' => $data]);
    }
}
