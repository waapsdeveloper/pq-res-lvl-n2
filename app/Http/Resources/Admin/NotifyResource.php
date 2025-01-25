<?php

namespace App\Http\Resources\Admin;

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

    public static function toObject($notification, $lang = 'en')
    {
        // dd($notification);
        return [
            'id' => (string) $notification->id,
            'type' => $notification->type,
            'data' => $notification->data,
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at ? $notification->created_at->toDateTimeString() : null,
            'updated_at' => $notification->updated_at ? $notification->updated_at->toDateTimeString() : null,
        ];
    }
}
