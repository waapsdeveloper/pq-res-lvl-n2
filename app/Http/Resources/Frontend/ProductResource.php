<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\Helper;


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
        // $productProps = $obj->productProps ? optional($obj->productProps) : null;
        return [
            "id" => $obj->id,
            "name" => $obj->name,
            "price" => $obj->price,
            "identifier" => $obj->identifier,
            "image" => $image,
            "status" => ucfirst($obj->status),
            "description" => $obj->description,
            'discount' => $obj->discount,
            "category_id" => $obj->category_id,
            "category" => $category,
            "restaurant_id" => $obj->restaurant_id,
            "restaurant" => $obj->restaurant ?
                [
                    'id' => $obj->restaurant->id,
                    'name' => $obj->restaurant->name,
                    'phone' => $obj->restaurant->phone,
                    'website' => $obj->restaurant->website,
                    'email' => $obj->restaurant->email,
                    'rating' => $obj->restaurant->rating,
                ] : [],
            "variation" => $obj->productProps->map(function ($prodProps) {
                return [
                    "meta_key" => $prodProps->meta_key,
                    "meta_value" => $prodProps->meta_value,
                    "meta_key_type" => $prodProps->meta_key_type
                ];
            }) ?? [],
            // "variation_id" => $obj->variation ? [
            //     "id" => $obj->variation->id,
            //     "meta_value" => $obj->variation->meta_value,
            //     "description" => $obj->variation->description
            // ] : [],
        ];
    }
}
