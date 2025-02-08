<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RTableBookingResource extends JsonResource
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
            "customer" => $obj->customer,
            "order_id" => $obj->order_id,
            "booking_start" => $obj->booking_start,
            "booking_end" => $obj->booking_end,
            "no_of_seats" => $obj->no_of_seats,
            "description" => $obj->description,
            "status" => ucfirst($obj->status),
        ];
    }
}
