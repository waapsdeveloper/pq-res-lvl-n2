<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Message\ReplyRequest;
use App\Http\Requests\Admin\Message\StoreMessage;
use App\Http\Requests\Admin\Message\UpdateMessage;
use App\Http\Requests\Admin\Message\UpdateStatusMessageRequest;
use App\Http\Resources\Admin\MessageResource;
use App\Models\Message;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $active_restaurant = Helper::getActiveRestaurantId();
        $resID = $request->restaurant_id == -1 ? $active_restaurant->id : $request->restaurant_id;

        $query = Message::query()
            ->where('restaurant_id', $resID)
            ->orderByDesc('id');

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);
        $data->getCollection()->transform(function ($item) {
            return new MessageResource($item);
        });

        return ServiceResponse::success("Messages list retrieved successfully", ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessage $request)
    {
        $data = $request->validated();
        $message = Message::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'message' => $data['message'],
            'restaurant_id' => $data['restaurant_id'],
            'status' => 'pending',
        ]);

        return ServiceResponse::success(
            'Message created successfully',
            ['message' => $message]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $message = Message::find($id);
        if (!$message) {
            return ServiceResponse::error("message not found", 404);
        }
        $data =  new MessageResource($message);
        return ServiceResponse::success("Message details retrieved successfully", ['message' => $data]);
    }

    public function update(UpdateMessage $request)
    {
        $data = $request->validated();
        $message = Message::find($data['id']);
        $message->update([
            'name' => $data['name'] ?? $message->name,
            'email' => $data['email'] ?? $message->email,
            'phone' => $data['phone'] ?? $message->phone,
            'message' => $data['message'] ?? $message->message,
            'status' => $data['status'] ?? $message->status,
            'restaurant_id' => $data['restaurant_id'] ?? $message->restaurant_id,
        ]);


        return ServiceResponse::success(
            'Message Updated successfully',
            ['message' => $message]
        );
    }
    public function destroy(string $id)
    {
        $message = Message::find($id);
        if (!$message) {
            return ServiceResponse::error("message not found", 404);
        }
        $message->delete();
        return ServiceResponse::success("message deleted successfully");
    }

    public function updateStatus(UpdateStatusMessageRequest $request)
    {
        $data = $request->validated();

        $message = Message::find($data['id']);
        if (!$message) {
            return ServiceResponse::error("message not found", 404);
        }

        $message->update(['status' => $data['status']]);
        return ServiceResponse::success("Message status updated successfully", ['message' => $message]);
    }
    public function reply(ReplyRequest $request, $email)
    {
        $messenger = Message::with('restaurant')->where('email', $email)->first();
        if (!$messenger) {
            return ServiceResponse::error('Messenger not found', 404);
        }
        $user = User::with('role')->find($request->id);

        $data = $request->validated();

        $restaurant = Restaurant::find((int) $request->restaurant_id);
        $data = [
            'mail_title' => 'Welcome to Our Service!',
            'restaurant_phone' => $restaurant->phone,
            'menu_url' => $restaurant->website . '/menu',
            'messenger_name' => $messenger->name,
            'restaurant_name' => $messenger->restaurant->name,
            'content' => $data['content'],
            'restaurant_email' => $messenger->restaurant->email,
            'reply_by_user_id' => $user->role->name,
        ];

        if (!$messenger) {
            return ServiceResponse::error('Messenger not found', 404);
        }

        // Mail::send('mail.mail', $data, function ($message) use ($email) {
        //     $message->to($email)
        //         ->subject('Reply from Local Craft Food');
        // });
        Mail::to($email)->send(new \App\Mail\Mail($data));

        return ServiceResponse::success("Email sent successfully to $email");
    }
}
