<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Restaurant;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);

        $query = Restaurant::query();

        $data = $query->paginate(20, ['*'], 'page', $page);
        return self::success("Trial list successfully", ['data' => $data]);
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
            'name' => 'required|string|min:3|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|regex:/^[0-9]{10,15}$/',
            'email' => 'nullable|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'website' => 'nullable|url|max:255',
            // 'opening_hours' => 'required|json', // Ensure valid JSON format
            'description' => 'nullable|string|max:1000',
            // 'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        // If validation fails
        if ($validation->fails()) {
            return self::failure($validation->errors()->first());
        }

        $restaurant = Restaurant::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'opening_hours' => $data['opening_hours'],
            'description' => $data['description'] ?? null,
            'rating' => $data['rating'] ?? 0, // Default rating to 0 if not provided
        ]);

        return self::success('Login successful', ['restaurant' => $restaurant]);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        // Attempt to find the restaurant by ID
        $restaurant = Restaurant::find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return self::failure("Restaurant not found", 404);
        }

        // Return a success response with the restaurant data
        return self::success("Restaurant details retrieved successfully", ['restaurant' => $restaurant]);
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
