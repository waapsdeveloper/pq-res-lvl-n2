<?php

namespace App\Http\Resources\Admin;

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
            "name" => "Customer Name Sample",
            "booking_start" => $obj->booking_start,
            "no_of_seats" => $obj->no_of_seats,
            // "restaurant" => $obj->restaurant,
            "identifier" => $obj->identifier,
            // "no_of_seats" => $obj->no_of_seats,
            // "description" => $obj->description,
            // "floor" => $obj->floor,
            "status" => ucfirst($obj->status),  // If you have a 'status' field in your rtable, you can add it here
        ];
    }
}
