<?php

namespace App\Http\Resources\Admin;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
        $address = $obj->userDetail ? optional($obj->userDetail)->address : null;
        $city = $obj->userDetail ? optional($obj->userDetail)->city : null;
        $state = $obj->userDetail ? optional($obj->userDetail)->state : null;
        $country = $obj->userDetail ? optional($obj->userDetail)->country : null;
        // dd($obj->userDetail);
        return [
            "id" => $obj->id,
            "name" => $obj->name,
            "email" => $obj->email,
            "phone" => $obj->phone,
            "role_id" => $obj->role_id,
            "role" => $obj->role ? $obj->role->name : '',
            "status" => $obj->status,
            "created_at" => $obj->created_at,
            "updated_at" => $obj->updated_at,
            "image" => $image,
            "address" => $address,
            "city" => $city,
            "state" => $state,
            "country" => $country,
        ];
    }
}
