<?php

namespace App\Http\Resources\Frontend;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FrontendMenuResource extends JsonResource
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
    public function toObject($obj, $lang = 'en')
    {
        return [
            "id" => $obj->id,
            "name" => $obj->name,
            'category_id' => $obj->category_id,
            'restaurant_id' => $obj->restaurant_id,
            'identifier' => $obj->identifier,
            'description' => $obj->description,
            "image" => Helper::returnFullImageUrl($obj->image),
            "status" => $obj->status,
            "restaurant" => $obj->restaurant ? [
                'id' => $obj->restaurant->id,
                'name' => $obj->restaurant->name,
                'phone' => $obj->restaurant->phone,
                'website' => $obj->restaurant->website,
                'email' => $obj->restaurant->email,
                'rating' => $obj->restaurant->rating,
            ] : [],
        ];
    }
}
