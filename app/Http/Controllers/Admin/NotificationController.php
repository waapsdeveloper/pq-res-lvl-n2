<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\NotifyResource;
use App\Models\Notification;
use App\Models\Order;

class NotificationController extends Controller
{

    // public function getNotifications()
    // {

    //     $notifications = Notification::query()->paginate(15);
    //     $data = new NotifyResource($notifications);
    //     return response()->json(ServiceResponse::success('getNotifications', $data));
    // }
    public function getNotifications(Request $request)
    {
        $page = $request->input('page', 1); // Current page
        $perpage = $request->input('perpage', 15); // Items per page

        $notifications = Notification::query()->orderBy('created_at', 'desc')->paginate($perpage, ['*'], 'page', $page);

        $data = $notifications->getCollection()->transform(function ($item) {
            return new NotifyResource($item);
        });

        return response()->json(ServiceResponse::success('Notifications fetched successfully', [
            'data' => $data,
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'total_pages' => $notifications->lastPage(),
                'total_items' => $notifications->total(),
                'per_page' => $notifications->perPage(),
            ],
        ]));
    }

    /**
     * Get unread notifications for a user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function getUnreadNotifications(Request $request)
    {
        $page = $request->input('page', 1); // Current page
        $perpage = $request->input('perpage', 15); // Items per page

        $notifications = Notification::query()
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')->paginate($perpage, ['*'], 'page', $page);

        $data = $notifications->getCollection()->transform(function ($item) {
            return new NotifyResource($item);
        });

        return response()->json(ServiceResponse::success('Notifications fetched successfully', [
            'data' => $data,
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'total_pages' => $notifications->lastPage(),
                'total_items' => $notifications->total(),
                'per_page' => $notifications->perPage(),
            ],
        ]));
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
        $notification->update(['read_at' => now()]);

        return response()->json(ServiceResponse::success(
            'All notification as read!',
            $notification
        ));
    }
}
