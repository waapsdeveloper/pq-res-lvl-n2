<?php

namespace App\Http\Resources\Admin;

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
        return [
            "id" => $obj->id,
            "name" => $obj->name,
            "email" => $obj->email,
            "phone" => $obj->phone,
            "address" => $obj->address,
            "role_id" => $obj->role_id ? $obj->role->name : '',
            "role" => $obj->role ? $obj->role->name : '',
            "status" => $obj->status,
            "created_at" => $obj->created_at,
            "updated_at" => $obj->updated_at,
            "image" => $obj->image
            //     "user_detail" => $obj->userDetail->map(function ($item) {
            //         return [
            //             ""
            //         ];



        ];
    }
}
