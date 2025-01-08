<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TableBookingResource extends JsonResource
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
            "restaurant" => $obj->restaurant,
            "identifier" => $obj->identifier,
            "day" => $obj->day,
            "date" => $obj->date,
            "days" => $obj->days,
            "status" => ucFirst($obj->status),
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
            'res_timings' => $obj->restaurantTimings->map(function ($resSchedule) {
                return [
                    'restaurant' => $resSchedule->restaurant,
                    'day' => $resSchedule->day,
                    'start_time' => $resSchedule->start_time,
                    'end_time' => $resSchedule->end_time,
                ];
            }),
        ];
    }
}
