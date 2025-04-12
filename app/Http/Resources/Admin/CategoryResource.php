<?php

namespace App\Http\Resources\Admin;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
        return [
            "id" => $obj->id,
            "name" => $obj->name,
            "description" => $obj->description,
            "image" => $image,
            "restaurant_id" => $obj->restaurant_id,
            "category" => $obj->category,
            "status" => ucfirst($obj->status),
            'product_count' => $obj->productsCount(),
        ];
    }
}
