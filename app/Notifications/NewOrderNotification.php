<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;
    public $order;
    public $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($user, $order)
    {
        $this->user = $user;  // Correct assignment
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'New order received!',
            'order_id' => $this->order->order_number,  // Dynamic order ID
            'customer_name' => $this->order->customer->name, // Assuming order has a customer relation
            'total_price' => $this->order->total_price,
            'url' => url("/admin/orders/{$this->order->id}")  // Redirect to order detail page
        ];
    }
}
