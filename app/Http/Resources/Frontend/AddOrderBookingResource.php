<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddOrderBookingResource extends JsonResource
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

    /**
     * Transform the resource into an object format.
     *
     * @param mixed $obj
     * @param string $lang
     * @return array<string, mixed>
     */
    public static function toObject($obj, $lang = 'en')
    {
        dd($obj);
        return [
            'id' => $obj->id,
            'restaurant_id' => $obj->restaurant_id,
            'rtable_booking_id' => $obj->rtable_booking_id,
            'rtable_id' => $obj->rtable_id,
            'booking_start' => $obj->booking_start,
            'booking_end' => $obj->booking_end,
            'no_of_seats' => $obj->no_of_seats,
            'res_timing' => $obj->restaurant->timings->map(function ($restaurant) {

                // dd($restaurant->timings);
                return [
                    'day' => $restaurant->day, // Include restaurant timings directly
                    'start_time' => $restaurant->start_time, // Include restaurant timings directly
                    'end_time' => $restaurant->end_time, // Include restaurant timings directly
                    'status' => $restaurant->status, // Include restaurant timings directly

                ];
            }),
        ];
    }
}
