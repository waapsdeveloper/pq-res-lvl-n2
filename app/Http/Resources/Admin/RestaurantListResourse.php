<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantListResourse extends JsonResource
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
            "address" => $obj->address,
            "phone" => $obj->phone ?? null,
            "email" => $obj->email ?? null,
            "website" => $obj->website ?? null,
            "description" => $obj->description ?? null,
            "rating" => $obj->rating ?? 0, // Default rating to 0 if not set
            "status" => $obj->status,
            // "opening_hours" => $obj->opening_hours ?? null,

        ];
    }
}
