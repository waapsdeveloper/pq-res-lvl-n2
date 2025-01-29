<?php

namespace App\Http\Resources\Frontend;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PopularProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return self::toObject($this);
    }
    public static function toObject($obj, $lang = 'en')
    {
        // dd($obj);
        return [
            "id" => $obj->id,
            "description" => $obj->description,
            "category_id" => $obj->category_id,
            "name" => $obj->name,
            "price" => $obj->price,
            "image" => Helper::returnFullImageUrl($obj->image),
            "status" => $obj->status,
            "productProps" => $obj->productProps ? $obj->productProps->map(function ($productProp) {
                return [
                    "id" => $productProp->id,
                    "type" => $productProp->type,
                    "meta_key" => $productProp->meta_key,
                    "meta_value" => $productProp->meta_value,
                ];
            }) : [],
            "category" => $obj->category ? [
                "id" => $obj->category->id,
                "name" => $obj->category->name,
                "description" => $obj->category->description,
                "image" => Helper::returnFullImageUrl($obj->category->image),
                "status" => $obj->category->status,
            ] : [],

            "variation" => $obj->variation ? $obj->variation->map(function ($variation) {
                return [
                    "id" => $variation->id,
                    "type" => $variation->type,
                    "meta_key" => $variation->meta_key,
                    "meta_value" => $variation->meta_value,
                ];
            }) : [],

        ];
    }
}
