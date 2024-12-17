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
            "status" => $obj->status,
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
