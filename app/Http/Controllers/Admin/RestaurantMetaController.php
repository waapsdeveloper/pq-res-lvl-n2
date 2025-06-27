<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantMeta;
use App\Helpers\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RestaurantMetaController extends Controller
{
    /**
     * Display a listing of meta data for a restaurant
     */
    public function index(Request $request, $restaurantId)
    {
        $restaurant = Restaurant::find($restaurantId);
        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $meta = RestaurantMeta::where('restaurant_id', $restaurantId)->get();
        
        return ServiceResponse::success('Meta data retrieved successfully', ['meta' => $meta]);
    }

    /**
     * Store a newly created meta data
     */
    public function store(Request $request, $restaurantId)
    {
        $validator = Validator::make($request->all(), [
            'meta_key' => 'required|string|max:255',
            'meta_value' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $restaurant = Restaurant::find($restaurantId);
        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $meta = RestaurantMeta::create([
            'restaurant_id' => $restaurant->id,
            'meta_key' => $request->meta_key,
            'meta_value' => $request->meta_value,
        ]);

        return ServiceResponse::success('Meta data created successfully', ['meta' => $meta]);
    }

    /**
     * Display the specified meta data
     */
    public function show($restaurantId, $metaId)
    {
        $restaurant = Restaurant::find($restaurantId);
        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $meta = RestaurantMeta::where('restaurant_id', $restaurantId)
                             ->where('id', $metaId)
                             ->first();

        if (!$meta) {
            return ServiceResponse::error('Meta data not found');
        }

        return ServiceResponse::success('Meta data retrieved successfully', ['meta' => $meta]);
    }

    /**
     * Update the specified meta data
     */
    public function update(Request $request, $restaurantId, $metaId)
    {
        $validator = Validator::make($request->all(), [
            'meta_value' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $restaurant = Restaurant::find($restaurantId);
        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $meta = RestaurantMeta::where('restaurant_id', $restaurantId)
                             ->where('id', $metaId)
                             ->first();

        if (!$meta) {
            return ServiceResponse::error('Meta data not found');
        }

        $meta->update([
            'meta_value' => $request->meta_value,
        ]);

        return ServiceResponse::success('Meta data updated successfully', ['meta' => $meta]);
    }

    /**
     * Remove the specified meta data
     */
    public function destroy($restaurantId, $metaId)
    {
        $restaurant = Restaurant::find($restaurantId);
        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $meta = RestaurantMeta::where('restaurant_id', $restaurantId)
                             ->where('id', $metaId)
                             ->first();

        if (!$meta) {
            return ServiceResponse::error('Meta data not found');
        }

        $meta->delete();

        return ServiceResponse::success('Meta data deleted successfully');
    }

    /**
     * Get meta data by key
     */
    public function getByKey(Request $request, $restaurantId)
    {
        $validator = Validator::make($request->all(), [
            'meta_key' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $restaurant = Restaurant::find($restaurantId);
        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $meta = RestaurantMeta::where('restaurant_id', $restaurantId)
                             ->where('meta_key', $request->meta_key)
                             ->first();

        if (!$meta) {
            return ServiceResponse::error('Meta data not found');
        }

        return ServiceResponse::success('Meta data retrieved successfully', ['meta' => $meta]);
    }

    /**
     * Store or update meta data by key
     */
    public function storeOrUpdate(Request $request, $restaurantId)
    {
        $validator = Validator::make($request->all(), [
            'meta_key' => 'required|string|max:255',
            'meta_value' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $restaurant = Restaurant::find($restaurantId);
        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $meta = RestaurantMeta::updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'meta_key' => $request->meta_key,
            ],
            [
                'meta_value' => $request->meta_value,
            ]
        );

        return ServiceResponse::success('Meta data stored successfully', ['meta' => $meta]);
    }
} 