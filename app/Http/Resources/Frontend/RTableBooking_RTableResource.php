<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RTableBooking_RTableResource extends JsonResource
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
            "rtable_id" => is_array($obj->rtable_id) ? $obj->rtable_id : [$obj->rtable_id],
            "rtable_booking_id" => $obj->rtable_booking_id,
            "booking_start" => $obj->booking_start,
            "booking_end" => $obj->booking_end,
        ];
    }
}
