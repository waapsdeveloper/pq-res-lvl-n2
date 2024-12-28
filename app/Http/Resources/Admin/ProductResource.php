<?php

namespace App\Http\Resources\Admin;

use App\Helpers\Helper;
use App\Models\ProductProps;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $obj = self::toObject($this);
        return $obj;
    }

    public static function toObject($obj, $lang = 'en')
    {

        $image = Helper::returnFullImageUrl($obj->image);
        $category = $obj->category_id ? optional($obj->category)->name : null;
        $restaurant = $obj->restaurant_id ? optional($obj->restaurant)->name : null;
        $sizes = ProductProps::where('product_id', $obj->id)->where('meta_key', 'size')->first();
        $spicy = ProductProps::where('product_id', $obj->id)->where('meta_key', 'spicy')->first();
        $type = ProductProps::where('product_id', $obj->id)->where('meta_key', 'type')->first();
        // dd($obj->productProps);
        return [
            "id" => $obj->id,
            "name" => $obj->name,
            "price" => $obj->price,
            "image" => $image,
            "status" => $obj->status,
            'discount' => $obj->discount,
            "category_id" => $category,
            "restaurant_id" => $restaurant,
            "sizes" => $sizes,
            "spicy" => $spicy,
            "type" => $type,
        ];
    }
}
