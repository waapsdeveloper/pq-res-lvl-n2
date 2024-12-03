<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\RtableResource;
use App\Models\Rtable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RtableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);

        $query = Rtable::query();

        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new RtableResource($item);
        });

        // Return the response with image URLs included
        return self::success("Category list successfully", ['data' => $data]);
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
        $data = $request->all();

        // Validate the required fields
        $validation = Validator::make($data, [
            'restaurant' => 'nullable|integer|exists:restaurants,id', // Ensure the restaurant exists
            'identifier' => 'required|string|unique:rtables,identifier|min:3|max:255', // Unique table identifier
            'location' => 'required|string|max:255', // Table location
            'description' => 'nullable|string|max:500', // Table description (nullable)
        ]);

        // If validation fails
        if ($validation->fails()) {
            return self::failure($validation->errors()->first());
        }

        // Create a new user (assuming the user model exists)
        $item = Rtable::create([
            'restaurant_id' => $data['restaurant'] ?? 0,
            'identifier' => $data['identifier'],
            'location' => $data['location'],
            'description' => $data['description'] ?? null, // Default to null if not provided
        ]);

        return self::success('Rtable store successful', ['item' => $item]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        // Attempt to find the restaurant by ID
        $restaurant = Rtable::find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return self::failure("Rtable not found", 404);
        }

        // Return a success response with the restaurant data
        return self::success("Rtable details retrieved successfully", ['Rtable' => $restaurant]);
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
        // Attempt to find the restaurant by ID
        $restaurant = Rtable::find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return self::failure("user not found", 404);
        }

        // Delete the restaurant
        $restaurant->delete();

        // Return a success response
        return self::success("User deleted successfully.");
    }
}
