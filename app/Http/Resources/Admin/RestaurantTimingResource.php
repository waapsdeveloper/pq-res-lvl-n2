<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantTimingResource extends JsonResource
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
        return [
            "id" => $obj->id,
            "restaurant_id" => $obj->restaurant_id,
            "restaurant" => $obj->restaurantDetail ? [
                "name" => $obj->restaurantDetail->name,
                "status" => $obj->restaurantDetail->status,
                // "address" => $obj->restaurantDetail->address,
                // "phone" => $obj->restaurantDetail->phone,
                // "email" => $obj->restaurantDetail->email,
                // "website" => $obj->restaurantDetail->website,
                // "description" => $obj->restaurantDetail->description,
                // "rating" => 4.8,
            ] : null,
            "day" => $obj->day,
            "start_time" => $obj->start_time,
            "end_time" => $obj->end_time,
            "status" => ucfirst($obj->status),
        ];
    }
}
