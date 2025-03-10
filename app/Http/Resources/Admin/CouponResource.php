<?php

namespace App\Http\Resources\Admin;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            "code" => $obj->code,
            "discount_value" => $obj->discount_value,
            "discount_type" => $obj->discount_type,
            "usage_limit" => $obj->usage_limit,
            "usage_count" => $obj->used_count,
            "expires_at" => $obj->expires_at,
            "is_active" => $obj->is_active,
        ];
    }
}
