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
            "variation" => $obj->productProps->map(function ($prodProps) {
                return [
                    "meta_key" => $prodProps->meta_key,
                    "meta_value" => $prodProps->meta_value,
                    "meta_key_type" => $prodProps->meta_key_type,
                ];
            }) ?? [],
            "category" => $obj->category ? [
                "id" => $obj->category->id,
                "name" => $obj->category->name,
                "description" => $obj->category->description,
                "image" => Helper::returnFullImageUrl($obj->category->image),
                "status" => $obj->category->status,
            ] : [],


        ];
    }
}
