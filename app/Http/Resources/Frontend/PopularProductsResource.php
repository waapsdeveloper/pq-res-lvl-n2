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
            "type" => $obj->productProp->type ?? null,
            "meta_key" => $obj->productProp->meta_key ?? null,
            "meta_value" => $obj->productProp->meta_value ?? null,
            "category" => $obj->category ? [
                "name" => $obj->category->name,
                "description" => $obj->category->description,
                "image" => Helper::returnFullImageUrl($obj->category->image),
                "status" => $obj->category->status,
            ] : [],
            "restaurant" => $obj->restaurant ? [
                "name" => $obj->restaurant->name,
                "description" => $obj->restaurant->description,
                "image" => Helper::returnFullImageUrl($obj->restaurant->image),
                "status" => $obj->restaurant->status,
            ] : [],
        ];
    }
}
