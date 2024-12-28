<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\DateHelper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Restaurant\UpdateRestaurant;
use App\Http\Requests\Admin\Restaurant\StoreRestaurant;
use App\Http\Resources\Admin\RestaurantListResourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\RestaurantTiming;
use App\Models\Restaurant;
use App\Models\RestaurantTimings;
use Illuminate\Support\Facades\Log;
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
        $filters = $request->input('filters', null);

        $query = Restaurant::query()->with('timings')->orderBy('id', 'desc');
        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($filters) {
            $filters = json_decode($filters, true); // Decode JSON string into an associative array

            if (isset($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (isset($filters['address'])) {
                $query->where('address', 'like', '%' . $filters['address'] . '%');
            }

            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }
        }
        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new RestaurantListResourse($item);
        });

        // Return the response with image URLs included
        return ServiceResponse::success("Trial list successfully", ['data' => $data]);
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
        $data = $request->validated();
        // Create the restaurant
        $restaurant = Restaurant::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'active',
            'image' => $data['image'] ?? null,
            'favicon' => $data['favicon'] ?? null,
            'logo' => $data['logo'] ?? null,
            'copyright_text' => $data['copyright_text'] ?? null,
            'rating' => $data['rating'] ?? 0,
        ]);

        // Process image, favicon, and logo (Base64 conversion)
        foreach (['image', 'favicon', 'logo'] as $field) {
            if (isset($data[$field]) && $data[$field]) {
                $url = Helper::getBase64ImageUrl($data[$field], 'restaurant');
                $restaurant->update([$field => $url]);
            }
        }

        foreach ($data['schedule'] as $day => $scheduleItem) {
            if (!empty($scheduleItem)) {
                RestaurantTiming::create([
                    'restaurant_id' => $restaurant->id,
                    'day' => ucfirst($scheduleItem['day']), // Din ka naam proper capitalization ke saath
                    'start_time' => $scheduleItem['start_time'], // Start time
                    'end_time' => $scheduleItem['end_time'], // End time
                    'status' => $scheduleItem['status'] ?? 'inactive', // Status, default inactive
                ]);
            }
        }

        return ServiceResponse::success('Store successful', ['restaurant' => $restaurant]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        // Attempt to find the restaurant by ID
        $restaurant = Restaurant::with('timings')->find($id);
        $restaurant['image'] = Helper::returnFullImageUrl($restaurant->image);
        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return ServiceResponse::error("Restaurant not found", 404);
        }

        // Return a success response with the restaurant data
        return ServiceResponse::success("Restaurant details retrieved successfully", ['restaurant' => $restaurant]);
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
        $data = $request->validated();

        $restaurant = Restaurant::find($id);


        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }
        // return response()->json([$data]);
        // Delete old images
        foreach (['image', 'favicon', 'logo'] as $field) {
            if (isset($data[$field]) && $data[$field] && $restaurant->{$field}) {
                Helper::deleteImage($restaurant->{$field});
            }
        }

        // Update restaurant details (using fill and save for cleaner code)
        $restaurant->fill([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'] ?? $restaurant->phone,
            'email' => $data['email'] ?? $restaurant->email,
            'website' => $data['website'] ?? $restaurant->website,
            'description' => $data['description'] ?? $restaurant->description,
            'status' => $data['status'] ?? $restaurant->status,
            'copyright_text' => $data['copyright_text'] ?? $restaurant->copyright_text,
            'rating' => $data['rating'] ?? $restaurant->rating,
        ]);

        $restaurant->save(); // Save the changes

        // Process new images
        foreach (['image', 'favicon', 'logo'] as $field) {
            if (isset($data[$field]) && $data[$field]) {
                $url = Helper::getBase64ImageUrl($data[$field], 'restaurant');
                $restaurant->update([$field => $url]); // Update only the image field
            }
        }

        // Delete existing timings for this restaurant before adding new ones
        RestaurantTiming::where('restaurant_id', $restaurant->id)->delete();

        foreach ($data['schedule'] as $day => $scheduleItem) {
            if (!empty($scheduleItem)) {
                Log::info("schedule", $scheduleItem);
                RestaurantTiming::create([
                    'restaurant_id' => $restaurant->id,
                    'day' => ucfirst($scheduleItem['day']), // Din ka naam proper capitalization ke saath
                    'start_time' => $scheduleItem['start_time'], // Start time
                    'end_time' => $scheduleItem['end_time'], // End time
                    'status' => $scheduleItem['status'] ?? 'inactive', // Status, default inactive
                ]);
            }
        }

        return ServiceResponse::success('Update successful', ['restaurant' => $restaurant]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the restaurant by ID
        $restaurant = Restaurant::find($id);
        RestaurantTiming::where('restaurant_id', $restaurant->id)->delete();

        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return ServiceResponse::error("Restaurant not found", 404);
        }

        // Delete the restaurant
        $restaurant->delete();

        // Return a success response
        return ServiceResponse::success("Restaurant deleted successfully.");
    }

    public function updateImage(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $validator = Validator::make($request->all(), [
            'image' => 'nullable|string|mimes:jpg,jpeg,png,gif,webp.bmp|max:2048',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $data = $request->all();

        if (isset($data['image'])) {
            $url = Helper::getBase64ImageUrl($data);
            $restaurant->update([
                'image' => $url,
            ]);
        }

        return ServiceResponse::success('Update successful', ['restaurant' => $restaurant]);
    }

    public function updateLogo(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp.bmp|max:2048',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $data = $request->all();

        if (isset($data['logo'])) {
            $url = Helper::getBase64ImageUrl($data);
            $restaurant->update([
                'logo' => $url,
            ]);
        }

        return ServiceResponse::success('Update successful', ['restaurant' => $restaurant]);
    }
    public function updateFavicon(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $validator = Validator::make($request->all(), [
            'favicon' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp.bmp|max:2048',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $data = $request->all();

        if (isset($data['favicon'])) {
            $url = Helper::getBase64ImageUrl($data);
            $restaurant->update([
                'favicon' => $url,
            ]);
        }

        return ServiceResponse::success('Update successful', ['restaurant' => $restaurant]);
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|exists:restaurants,id',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }
        $ids = $request->input('ids', []);

        RestaurantTiming::whereIn('restaurant_id', $ids)->delete();

        Restaurant::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }
}
