<?php

namespace App\Http\Resources\Admin;

use App\Helpers\Helper;
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
        $image = Helper::returnFullImageUrl($obj->image);
        $favicon = Helper::returnFullImageUrl($obj->favicon);
        $logo = Helper::returnFullImageUrl($obj->logo);

        return [
            "id" => $obj->id,
            "name" => $obj->name,
            "address" => $obj->address,
            "phone" => $obj->phone ?? null,
            "email" => $obj->email ?? null,
            "website" => $obj->website ?? null,
            "description" => $obj->description ?? null,
            "rating" => $obj->rating ?? 0, // Default rating to 0 if not set
            "status" => ucFirst($obj->status),
            "created_at" => $obj->created_at,
            "updated_at" => $obj->updated_at,
            "image" => $image,
            "favicon" => $favicon,
            "logo" => $logo,
            "copyright_text" => $obj->copyright_text ?? null,
            "schedule" => $obj->timings->map(function ($item) {
                return [
                    'day' => $item->day,
                    'start_time' => $item->start_time,
                    'end_time' => $item->end_time,
                    'status' => ucFirst($item->status),
                ];
            }),
        ];
    }
}
