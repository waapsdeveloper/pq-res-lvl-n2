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

    public function aboutUs($categoryId)
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return ServiceResponse::error('Category not found', 404);
        }

        $products = Product::where('category_id', $categoryId)->get();

        return ServiceResponse::success('About Us', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'image' => Helper::returnFullImageUrl($category->image),
            ],
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => Helper::returnFullImageUrl($product->image),
                    'description' => $product->description,
                ];
            }),

        ]);
    }
    public function lowestPrice()
    {
        $products = Product::orderBy('price', 'asc')->first();
        return ServiceResponse::success('Lowest Price', ['products' => $products]);
    }
}
