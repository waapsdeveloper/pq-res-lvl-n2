<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $obj = self::toObject($this);
        $address = $this->userDetails; // Fetch the related UserAddresses
        if ($address->count() > 0) {
            $obj['address'] = $address; // Assuming 'address' is a column in the related table
        } else {
            $obj['address'] = 'no address found';
        }

        return $obj;
    }

    public static function toObject($obj, $lang = 'en')
    {
        $image = Helper::returnFullImageUrl($obj->image);
        return [
            "id" => $obj->id,
            "name" => $obj->name,
            "email" => $obj->email,
            "phone" => $obj->phone,
            "image" => $image,
            // "address" => $obj->address,
            "status" => ucfirst($obj->status),
        ];
    }
}
