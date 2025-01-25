<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
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
   
}
