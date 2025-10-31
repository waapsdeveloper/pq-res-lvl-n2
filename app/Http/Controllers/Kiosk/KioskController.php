<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ServiceResponse;
use App\Models\Product;
use App\Models\Category;
use App\Http\Resources\Frontend\ProductResource;
use App\Http\Resources\Admin\CategoryResource;

use App\Models\RestaurantMeta;



class KioskController extends Controller
{
  public function getCatalog(request $request)
  {
    $products=  Product::where('restaurant_id',$request->restaurant_id)->get();
    $categories=  Category::where('restaurant_id',$request->restaurant_id)->get();
     return ServiceResponse::success(
        'Kiosk catalog fetched successfully',
        [
          'products' => ProductResource::collection($products),
          'categories' => CategoryResource::collection($categories)
        ]
    );
    // Implementation for fetching kiosk catalog


  }

  /**
   * Return restaurant meta data for a given restaurant_id
   */
  public function getRestaurantMeta(Request $request)
  {
    $meta = RestaurantMeta::where('restaurant_id', $request->restaurant_id)->get();
    return ServiceResponse::success(
      'Restaurant meta fetched successfully',
      [
        'meta' => $meta
      ]
    );
  }


}
