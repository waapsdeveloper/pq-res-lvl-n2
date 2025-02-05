<?php

namespace App\Http\Resources\Frontend;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddOrderBookingResource extends JsonResource
{
    public function toArray($request)
    {
        return self::toObject($this);
    }

    public static function toObject($obj, $lang = 'en')
    {

        //return $obj;
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
                    'name' => $orderProduct->product->name,
                    'product_id' => $orderProduct->product_id,
                    'product_name' => $orderProduct->product->name ?? null,
                    'quantity' => $orderProduct->quantity,
                    'image' => Helper::returnFullImageUrl($orderProduct->product->image),
                    'price' => $orderProduct->price,
                    'notes' => $orderProduct->notes,
                    'variation' => $orderProduct->variation,
                    'variations' => $orderProduct->variation ? json_decode($orderProduct->variation) : [],
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
            'notification' => $obj->notification ?
                [
                    'id' => $obj->notification->id,
                    'notifiable_id' => $obj->notification->notifiable_id,
                    'title' => $obj->notification->data['title'],
                    'message' => $obj->notification->data['message'],
                    'read_at' => $obj->notification->read_at,
                    'created_at' => $obj->notification->created_at,

                ] : null,
            'table' => $obj->table ?? null,
            'created_at' => $obj->created_at,
            'updated_at' => $obj->updated_at,
        ];
    }
}
