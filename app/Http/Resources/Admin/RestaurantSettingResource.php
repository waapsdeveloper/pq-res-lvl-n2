<?php

namespace App\Http\Resources\Admin;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantSettingResource extends JsonResource
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
        $image = Helper::returnFullImageUrl($obj->restaurant->image);
        $favicon = Helper::returnFullImageUrl($obj->restaurant->favicon);
        $logo = Helper::returnFullImageUrl($obj->restaurant->logo);
        // dd($obj);
        return [

            "id" => $obj->id,
            "meta_key" => $obj->meta_key,
            "meta_value" => $obj->meta_value,
            "restaurant_id" => $obj->restaurant->id,
            "name" => $obj->restaurant->name,
            "address" => $obj->restaurant->address,
            "phone" => $obj->restaurant->phone ?? null,
            "email" => $obj->restaurant->email ?? null,
            "website" => $obj->restaurant->website ?? null,
            "description" => $obj->restaurant->description ?? null,
            "rating" => $obj->restaurant->rating ?? 0, // Default rating to 0 if not set
            "status" => ucfirst($obj->restaurant->status),
            "created_at" => $obj->restaurant->created_at,
            "updated_at" => $obj->restaurant->updated_at,
            "image" => $image,
            "favicon" => $favicon,
            "logo" => $logo,
            "copyright_text" => $obj->restaurant->copyright_text ?? null,
            "schedule" => $obj->timings->map(function ($item) {
                return [
                    'day' => $item->day,
                    'start_time' => $item->start_time,
                    'end_time' => $item->end_time,
                    'status' => ucfirst($item->status),
                ];
            }),

        ];
    }
}
