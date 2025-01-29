<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories()
    {
        $categories = Category::get();
        $categories['image'] = Helper::returnFullImageUrl($categories->image);
        return ServiceResponse::success('categories are retrived successfully', ['data' => $categories]);
    }
}
