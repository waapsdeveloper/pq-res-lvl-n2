<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactUs\StoreContactUs;
use App\Http\Requests\Admin\ContactUs\UpdateContactUs;
use App\Http\Resources\Admin\ContactUsResource;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
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

        $query = ContactUs::query()->where('restaurant_id', $resID)->orderByDesc('id');

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);
        $data->getCollection()->transform(function ($item) {
            return new ContactUsResource($item);
        });

        return ServiceResponse::success("Contact list retrieved successfully", ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactUs $request)
    {
        $data = $request->validated();
        $contact = ContactUs::create([
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
        $contact = ContactUs::find($id);
        if (!$contact) {
            return ServiceResponse::error("Contact not found", 404);
        }
        $data =  new ContactUsResource($contact);
        return ServiceResponse::success("Contact details retrieved successfully", ['contact' => $data]);
    }

    public function update(UpdateContactUs $request)
    {
        $data = $request->validated();
        $contact = ContactUs::find($data['id']);
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
        $contact = ContactUs::find($id);
        if (!$contact) {
            return ServiceResponse::error("Contact not found", 404);
        }
        $contact->delete();
        return ServiceResponse::success("Contact deleted successfully");
    }

    public function mail()
    {
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'url' => 'https://www.itsolutionstuff.com',
        ];

        Mail::send('emails.myTestMail', $data, function ($message) {
            $message->to('
            [email protected]')->subject('Laravel Testing Mail with Attachment');
            $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
            $message->from('
            [email protected]', 'ItSolutionStuff.com');
        });
    }
}
