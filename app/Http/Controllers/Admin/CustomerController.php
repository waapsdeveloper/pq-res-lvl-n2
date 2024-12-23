<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CustomerResource;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ServiceResponse;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);

        $query = User::query();

        // Optionally apply search filter if needed
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('role_id', 8)  // Assuming 'customer' role ID is 8
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);
        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new CustomerResource($item);
        });

        // Return the response with image URLs included
        return ServiceResponse::success("Customers list successfully", ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = User::with('userDetails')->find($id);
        // If the restaurant doesn't exist, return an error response
        if (!$customer) {
            return ServiceResponse::error("customer not found", 404);
        }
        // Return a success response with the restaurant data
        return ServiceResponse::success("customer details retrieved successfully", ['customer' => $customer]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
