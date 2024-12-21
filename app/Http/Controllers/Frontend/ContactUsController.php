<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactUs\StoreContactUs;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function store(StoreContactUs $request)
    {
        $data = $request->validated();
        $contact = ContactUs::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'message' => $data['message'],
        ]);


        $responseData = [
            'id' => $contact->id,
            'name' => $contact->name,
            'email' => $contact->email,
            'phone' => $contact->phone,
            'message' => $contact->message,
            'created_at' => $contact->created_at,
            'updated_at' => $contact->updated_at,
        ];

        return ServiceResponse::success(
            'Contact message sent to the company. They will reply as soon as possible.',
            ['contact' => $responseData]
        );
    }
}
