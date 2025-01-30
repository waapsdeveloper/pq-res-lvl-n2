<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Message\StoreMessage;
use App\Http\Requests\Admin\Message\UpdateMessage;
use App\Http\Resources\Admin\MessageResource;
use App\Models\Message;
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
            // ->where('restaurant_id', $resID)
            ->orderByDesc('id');

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);
        $data->getCollection()->transform(function ($item) {
            return new MessageResource($item);
        });

        return ServiceResponse::success("Contact list retrieved successfully", ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessage $request)
    {
        $data = $request->validated();
        $contact = Message::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'message' => $data['message'],
        ]);

        return ServiceResponse::success(
            'Contact message sent to the company. They will reply as soon as possible.',
            ['contact' => $contact]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contact = Message::find($id);
        if (!$contact) {
            return ServiceResponse::error("Contact not found", 404);
        }
        $data =  new MessageResource($contact);
        return ServiceResponse::success("Contact details retrieved successfully", ['contact' => $data]);
    }

    public function update(UpdateMessage $request)
    {
        $data = $request->validated();
        $contact = Message::find($data['id']);
        $contact->update([
            'name' => $data['name'] ?? $contact->name,
            'email' => $data['email'] ?? $contact->email,
            'phone' => $data['phone'] ?? $contact->phone,
            'message' => $data['message'] ?? $contact->message,
        ]);


        return ServiceResponse::success(
            'Contact message sent to the company. They will reply as soon as possible.',
            ['contact' => $contact]
        );
    }
    public function destroy(string $id)
    {
        $contact = Message::find($id);
        if (!$contact) {
            return ServiceResponse::error("Contact not found", 404);
        }
        $contact->delete();
        return ServiceResponse::success("Contact deleted successfully");
    }

    public function reply($email)
    {
        // Prepare the data for the email
        $user = Message::where('email', $email)->first();
        $data = [
            'mail_title' => 'Welcome to Our Service!',
            'restaurant_phone' => '1234567890',
            'menu_url' => 'https://localcraftfood.com/menu',
            'user_name' => $user->name,
            'restaurant_name' => 'Local Craft Food',
            'body' => 'This is the body of the email',
            'restaurant_email' => 'messages@localcraftfood.com',
        ];

        // Retrieve the user message by email

        // Check if the user exists before proceeding
        if (!$user) {
            return 'User not found';
        }

        // Retrieve the user's email from the database
        $email = (string)$user->email;

        // Send the email
        Mail::send('mail.mail', $data, function ($message) use ($email) {
            $message->to($email)
                ->subject('Reply from Local Craft Food');
        });

        return 'Mail sent successfully!';
    }
}
