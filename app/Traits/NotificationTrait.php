<?php

namespace App\Traits;

use App\Models\Notification;

trait NotificationTrait
{
    public function createNotification($order)
    {
        $notification = Notification::create([
            'type' => 'App\Notifications\NewOrderNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => 1, //User::find(1)->id,
            'data' => [
                'title' => 'New Order',
                'message' => 'You have a new order',
                'order_id' => $order->id,
            ],
            'read_at' => null,
        ]);
    }
}
