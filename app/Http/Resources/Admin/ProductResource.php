<?php

namespace App\Http\Resources\Admin;

use App\Helpers\Helper;
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

        return [
            "id" => $obj->id,
            "category" => $obj->category,
            "name" => $obj->name,
            "price" => $obj->price,
            "image" => $image,
            "status" => $obj->status,
        ];
    }
}
