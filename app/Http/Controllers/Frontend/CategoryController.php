<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories(Request $request)
    {
        $categories = Category::where('restaurant_id', (int) $request->restaurant_id)->get();

        $categories = $categories->map(function ($category) {
            $category->image = Helper::returnFullImageUrl($category->image);
            return $category;
        });

        return ServiceResponse::success('Categories retrieved successfully', ['data' => $categories]);
    }
}
