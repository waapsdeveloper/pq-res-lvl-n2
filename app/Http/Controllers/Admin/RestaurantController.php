<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Restaurant\UpdateRestaurant;
use App\Http\Requests\Restaurant\StoreRestaurant;
use App\Http\Resources\Admin\RestaurantListResourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Restaurant;
use Illuminate\Validation\ValidationException;
use PHPUnit\TextUI\Help;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);

        $query = Restaurant::query();

        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new RestaurantListResourse($item);
        });

        // Return the response with image URLs included
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
    public function store(StoreRestaurant $request)
    {
        //

        $data = $request->all();
        $data = $request->validated();

        // Validate the required fields
        // $validation = Validator::make($data, [
        //     // 'image' => 'required|string',
        //     'name' => 'required|string|min:3|max:255',
        //     'address' => 'required|string|max:500',
        //     'phone' => 'nullable|string', // |regex:/^[0-9]{10,15}$/
        //     'email' => 'nullable|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        //     'website' => ['nullable', 'regex:/^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([\/\w .-]*)*\/?$/'],
        //     // 'opening_hours' => 'required|json', // Ensure valid JSON format
        //     'description' => 'nullable|string|max:1000',
        //     'status' => 'nullable|string',
        //     // 'rating' => 'nullable|numeric|min:0|max:5',
        // ]);

        // // If validation fails
        // if ($validation->fails()) {
        //     return self::failure($validation->errors()->first());
        // }

        $restaurant = Restaurant::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'opening_hours' => $data['opening_hours'] ?? null,
            'description' => $data['description'] ?? null,
            'rating' => $data['rating'] ?? 0, // Default rating to 0 if not provided
            'status' => $data['status'] ?? 'active', // Default rating to 0 if not provided
        ]);


        // if ($data['image']) {
        //     $url = Helper::getBase64ImageUrl($data);
        //     $restaurant->update([
        //         'image' => $url
        //     ]);
        // }





        return ServiceResponse::success('Store successful', ['restaurant' => $restaurant]);
    }


    // public function store(StoreRestaurant $request)
    // {

    //     $data = $request->validated();  // Returns only validated data


    //     $restaurant = Restaurant::create([
    //         'name' => $data['name'],
    //         'address' => $data['address'],
    //         'phone' => $data['phone'] ?? null,
    //         'email' => $data['email'] ?? null,
    //         'website' => $data['website'] ?? null,
    //         'opening_hours' => $data['opening_hours'] ?? null,
    //         'description' => $data['description'] ?? null,
    //         'rating' => $data['rating'] ?? 0,
    //         'status' => $data['status'] ?? 'active',
    //     ]);

    //     return ServiceResponse::success('Store successful', ['restaurant' => $restaurant]);
    // }

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
    public function update(UpdateRestaurant $request, string $id)
    {

        $data = $request->all();


        // $validation = Validator::make($data, [
        //     'name' => 'required|string|min:3|max:255',
        //     'address' => 'required|string|max:500',
        //     'phone' => 'nullable|string', // |regex:/^[0-9]{10,15}$/
        //     'email' => 'nullable|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        //     'website' => ['nullable', 'regex:/^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([\/\w .-]*)*\/?$/'],
        //     'description' => 'nullable|string|max:1000',
        //     'status' => 'nullable|string',
        //     'rating' => 'nullable|numeric|min:0|max:5',
        // ]);


        // if ($validation->fails()) {
        //     return self::failure($validation->errors()->first());
        // }


        $restaurant = Restaurant::find($id);


        if (!$restaurant) {
            return self::failure('Restaurant not found');
        }


        $restaurant->update([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'] ?? $restaurant->phone,
            'email' => $data['email'] ?? $restaurant->email,
            'website' => $data['website'] ?? $restaurant->website,
            'opening_hours' => $data['opening_hours'] ?? $restaurant->opening_hours,
            'description' => $data['description'] ?? $restaurant->description,
            'rating' => $data['rating'] ?? $restaurant->rating,
            'status' => $data['status'] ?? $restaurant->status,
        ]);


        // if (isset($data['image'])) {
        //     // Assuming you have a helper method to handle image uploads
        //     $url = Helper::getBase64ImageUrl($data);
        //     $restaurant->update([
        //         'image' => $url
        //     ]);
        // }

        // Return a success response with the updated restaurant data
        return ServiceResponse::success('Update successful', ['restaurant' => $restaurant]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the restaurant by ID
        $restaurant = Restaurant::find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return self::failure("Restaurant not found", 404);
        }

        // Delete the restaurant
        $restaurant->delete();

        // Return a success response
        return self::success("Restaurant deleted successfully.");
    }
}
