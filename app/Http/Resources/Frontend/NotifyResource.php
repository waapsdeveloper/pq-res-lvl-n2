<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotifyResource extends JsonResource
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
            "slug" => $obj->slug,
        ];
    }
}
