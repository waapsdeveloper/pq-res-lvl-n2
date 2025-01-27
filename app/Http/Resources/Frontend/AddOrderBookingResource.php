<?php

namespace App\Http\Resources\Frontend;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddOrderBookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return self::toObject($this);
    }

    /**
     * Transform the resource into an object format.
     *
     * @param mixed $obj
     * @param string $lang
     * @return array<string, mixed>
     */
    public static function toObject($obj, $lang = 'en')
    {

        return [
            'id' => $obj->id,
            'order_number' => $obj->order_number,
            'type' => $obj->type,
            'status' => ucfirst($obj->status),
            'notes' => $obj->notes,
            'discount' => $obj->discount,
            'invoice_no' => $obj->invoice_no,
            'table_no' => $obj->table_no,
            'total_price' => $obj->total_price,
            'products' => $obj->orderProducts->map(function ($orderProduct) {
                return [
                    'product_id' => $orderProduct->product_id,
                    'product_name' => $orderProduct->product->name ?? null,
                    'quantity' => $orderProduct->quantity,
                    'image' => Helper::returnFullImageUrl($orderProduct->product->image),
                    'price' => $orderProduct->price,
                    'notes' => $orderProduct->notes,
                    // 'variation' => json_decode($orderProduct->variation),
                    'variation' => $orderProduct->variation,
                ];
            }),
            'customer' => $obj->customer ? [
                'id' => $obj->customer->id,
                'name' => $obj->customer->name,
                'phone' => $obj->customer->phone,
                'email' => $obj->customer->email,
            ] : null,
            'restaurant' => $obj->restaurant ?
                [
                    'id' => $obj->restaurant->id,
                    'name' => $obj->restaurant->name,
                    'phone' => $obj->restaurant->phone,
                    'email' => $obj->restaurant->email,
                    'status' => $obj->restaurant->status,
                ] : null,
                'notification' => $obj->getOrderIdFromNotification() ?
                [
                    'id' => $obj->notification->id,
                    'title' => $obj->notification->title,
                    'message' => $obj->notification->message,
                    'status' => $obj->notification->status,
                    
                ] : null,
            // 'restaurant_timings' => $obj->restaurant->timings->map(function ($timing) {
            //     return [
            //         'day' => $timing->day,
            //         'start_time' => $timing->start_time,
            //         'end_time' => $timing->end_time,
            //         'status' => $timing->status,
            //     ];
            // }),
            'table' => $obj->table ?? null,

        ];
    }
}
