<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckAvailabilityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
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
        return [
            'table_id' => $obj->id,
            'restaurant' => $obj->restaurant ? [
                'id' => $obj->restaurant->id,
                'name' => $obj->restaurant->name,
                'timings' => $obj->restaurant->timings->map(function ($timing) {
                    return [
                        'day' => $timing->day,
                        'start_time' => $timing->start_time,
                        'end_time' => $timing->end_time,
                    ];
                })->toArray(),
            ] : null,
            'booking_start' => $obj->booking_start,
            'booking_end' => $obj->booking_end,
        ];
    }
}
