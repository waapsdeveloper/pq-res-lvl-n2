<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;

class NotificationController extends Controller
{
    /**
     * Send a notification to a specific user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function sendNotification($orderId)
    {
        $user = User::where('role_id', [1, 2, 3, 4, 6]);
        $order = Order::findOrFail($orderId);

        $notify = $user->notify(new NewOrderNotification($order));
        return ServiceResponse::success($notify, 'Notification sent successfully!');
    }

    /**
     * Get all notifications for a user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function getNotifications()
    {

        $notifications = Notification::query()->paginate(15);

        return ServiceResponse::success('getNotifications', $notifications);
    }

    /**
     * Get unread notifications for a user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function getUnreadNotifications()
    {

        $unreadNotifications = Notification::query()
            ->whereNull('read_at')
            ->paginate(10);
        return ServiceResponse::success(
            'unread_notifications',
            $unreadNotifications,
        );
    }

    /**
     * Mark notifications as read.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function markAsRead($notificationId)
    {
        $notification = Notification::findOrFail($notificationId);

        // Mark all unread notifications as read
        $notification->unreadNotifications->markAsRead();

        return ServiceResponse::success(
            'All notification as read!',
            $notification
        );
    }
}
