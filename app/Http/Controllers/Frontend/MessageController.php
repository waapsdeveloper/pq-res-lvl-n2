<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Message\StoreMessage;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(StoreMessage $request)
    {
        $data = $request->validated();
        $contact = Message::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'message' => $data['message'],
            'restaurant_id' => $data['restaurant_id'],
        ]);

        $responseData = [
            'id' => $contact->id,
            'name' => $contact->name,
            'email' => $contact->email,
            'phone' => $contact->phone,
            'message' => $contact->message,
            'restaurant_id' => $contact->restaurant_id,
            'created_at' => $contact->created_at,
            'updated_at' => $contact->updated_at,
        ];

        return ServiceResponse::success(
            'Contact message sent to the company. They will reply as soon as possible.',
            ['contact' => $responseData]
        );
    }
}
