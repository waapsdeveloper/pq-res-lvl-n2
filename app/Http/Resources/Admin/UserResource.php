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
        // $image = Helper::returnFullImageUrl($obj->image);
        $address = $obj->userDetail ? $obj->userDetail->address : null;
        $city = $obj->userDetail ? $obj->userDetail->city : null;
        $state = $obj->userDetail ? $obj->userDetail->state : null;
        $country = $obj->userDetail ? $obj->userDetail->country : null;


        // dd($obj->role->name);
        return [
            "id" => $obj->id,
            "name" => $obj->name,
            "email" => $obj->email,
            "dial_code" => $obj->dial_code,
            "phone" => $obj->phone,
            "role_id" => $obj->role_id,
            "role" => in_array($obj->role_id, [null, 0]) ? 'customer' : ($obj->role ? $obj->role->name : ""),
            "status" => ucfirst($obj->status),
            "restaurant_id" => $obj->restaurant->id ?? null,
            "created_at" => $obj->created_at,
            "updated_at" => $obj->updated_at,
            "image" => $obj->image,
            "address" => $address,
            "city" => $city,
            "state" => $state,
            "country" => $country,
        ];
    }
}
