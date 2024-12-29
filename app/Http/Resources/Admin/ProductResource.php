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
        $restaurant = $obj->restaurant_id ? optional($obj->restaurant) : null;
        $props = ProductProps::where('product_id', $obj->id)->get();
        // dd($obj->productProps);
        return [
            "id" => $obj->id,
            "name" => $obj->name,
            "price" => $obj->price,
            "identifier" => $obj->identifier,
            "image" => $image,
            "status" => $obj->status,
            "description" => $obj->description,
            'discount' => $obj->discount,
            "category_id" => $obj->category_id,
            "category" => $category,
            "restaurant_id" => $obj->restaurant_id,
            "restaurant" => $restaurant,
            "props" => $props
        ];
    }
}
