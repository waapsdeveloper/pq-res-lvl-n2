<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
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
        // Get meta data from restaurant_meta table
        $meta = [];
        if ($obj->meta && $obj->meta->count() > 0) {
            foreach ($obj->meta as $metaItem) {
                $meta[$metaItem->meta_key] = $metaItem->meta_value;
            }
        }
        
        return [
            "id" => $obj->id,
            "name" => $obj->name,
            "address" => $obj->address,
            "status" => ucfirst($obj->status),
            "meta" => $meta, // Add meta data to response
        ];
    }
}
