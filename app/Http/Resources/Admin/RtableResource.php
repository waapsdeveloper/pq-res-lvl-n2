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
            "restaurant" => $obj->restaurant,
            "identifier" => $obj->identifier,
            "location" => $obj->location,
            "description" => $obj->description,
            "status" => $obj->status,  // If you have a 'status' field in your rtable, you can add it here
        ];
    }
}
