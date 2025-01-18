<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ContactUsResource;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $query = ContactUs::query()->where('restaurant_id', $request->restaurant_id);

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


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contact = ContactUs::find($id);

        if (!$contact) {
            return ServiceResponse::error("Contact not found", 404);
        }

        return ServiceResponse::success("Contact details retrieved successfully", ['contact' => $contact]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $contact = ContactUs::find($id);

    //     if (!$contact) {
    //         return ServiceResponse::error('Contact not found', 404);
    //     }

    //     $data = $request->validate([
    //         'name' => 'nullable|string|max:255',
    //         'email' => 'nullable|email|max:255',
    //         'phone' => 'nullable|string|max:20',
    //         'message' => 'nullable|string|max:1000',
    //     ]);

    //     $contact->update($data);

    //     return ServiceResponse::success('Contact message updated successfully', ['contact' => $contact]);
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     $contact = ContactUs::find($id);

    //     if (!$contact) {
    //         return ServiceResponse::error("Contact not found", 404);
    //     }

    //     $contact->delete();

    //     return ServiceResponse::success("Contact message deleted successfully.");
    // }
}
