<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RtableResource extends JsonResource
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
            "name" => $obj->name,
            "restaurant_id" => $obj->restaurant_id,
            "identifier" => $obj->identifier,
            "no_of_seats" => $obj->no_of_seats,
            "description" => $obj->description,
            "floor" => $obj->floor,
            "status" => $obj->status,
            'opening_hours' => $obj->restaurantTimings->map(function ($resSchedule) {
                return [
                    // 'restaurant_id' => $resSchedule->restaurant_id,
                    'day' => $resSchedule->day,
                    'start_time' => $resSchedule->start_time,
                    'end_time' => $resSchedule->end_time,
                ];
            }),
            'restaurant_detail' => $obj->restaurantDetail ? [
                "name" => $obj->restaurantDetail->name,
                "address" => $obj->restaurantDetail->address,
                "phone" => $obj->restaurantDetail->phone,
                // "email" => $obj->restaurantDetail->email,
                // "website" => $obj->restaurantDetail->website,
                // "description" => $obj->restaurantDetail->description,
                "rating" => 4.8,
                "status" => $obj->restaurantDetail->status,
            ] : null,
        ];
    }
}
