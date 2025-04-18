<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RestaurantTiming\StoreRestaurantTiming;
use App\Http\Requests\Admin\RestaurantTiming\UpdateRestaurantTiming;
use App\Http\Resources\Admin\RestaurantTimingResource;
use App\Models\Restaurant;
use App\Models\RestaurantTiming;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RestaurantTimingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perPage = $request->input('perpage', 10);
        $restaurantId = $request->input('restaurant_id', '');
        $filters = $request->input('filters', null);

        $active_restaurant = Helper::getActiveRestaurantId();
        $resID = $request->restaurant_id == -1 ? $active_restaurant->id : $request->restaurant_id;

        $query = RestaurantTiming::query()->where('restaurant_id', $resID);

        // Apply search filter if a search term is provided
        if ($search) {
            $query->where('day', 'like', '%' . $search . '%');
        }
        if ($filters) {
            if (is_string($filters)) {
                $filters = json_decode($filters, true); // Decode JSON to array
            }
            if (is_array($filters)) {
                if (isset($filters['day'])) {
                    $query->where('day', 'like', '%' . $filters['day'] . '%');
                }
                if (isset($filters['restaurant_id'])) {
                    $query->where('restaurant_id', 'like', '%' . $filters['restaurant_id'] . '%');
                }

                if (isset($filters['status'])) {
                    $query->where('status', $filters['status']);
                }
            }
        }

        // Apply filter for a specific restaurant ID if provided
        if ($restaurantId) {
            $query->where('restaurant_id', $restaurantId);
        }

        // Paginate the results
        $data = $query->paginate($perPage, ['*'], 'page', $page);

        // Transform the paginated collection with the resource
        $data->getCollection()->transform(function ($item) {
            return new RestaurantTimingResource($item);
        });

        // Return a successful response with the transformed data
        return ServiceResponse::success("Restaurant timing list retrieved successfully", ['data' => $data]);
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
    public function store(StoreRestaurantTiming $request)
    {
        $data = $request->validated();


        $timing = RestaurantTiming::create([
            'restaurant_id' => $data['restaurant_id'],
            'day' => ucfirst($data['day']),
            'start_time' => DateTime::createFromFormat('H:i:s', $data['start_time'])->format('H:i'),
            'end_time' => DateTime::createFromFormat('H:i:s', $data['end_time'])->format('H:i'),
            'status' => $data['status'],

        ]);


        return ServiceResponse::success(
            'RestaurantTiming store successful',
            ['timing' => $timing]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $restaurant_timing = RestaurantTiming::with('restaurantDetail')->find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$restaurant_timing) {
            return ServiceResponse::error("Restaurant Timing not found", 404);
        }

        // Return a success response with the restaurant data
        return ServiceResponse::success("Restaurant Timing details retrieved successfully", ['restaurant_timing' => $restaurant_timing]);
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
    public function update(UpdateRestaurantTiming $request, string $id)
    {
        $data = $request->validated();

        $restaurant_timing = RestaurantTiming::find($id);
        if (!$restaurant_timing) {
            return ServiceResponse::error('Restaurant timing not found');
        }

        $restaurant_timing->update([
            'restaurant_id' => $data['restaurant_id'] ?? $restaurant_timing->restaurant_id,
            'day' => ucfirst($data['day']) ?? $restaurant_timing->day,
            'start_time' => DateTime::createFromFormat('H:i:s', $data['start_time'])->format('H:i') ?? $restaurant_timing->start_time,
            'end_time' => DateTime::createFromFormat('H:i:s', $data['end_time'])->format('H:i') ?? $restaurant_timing->end_time,
            'status' => $data['status'] ?? $restaurant_timing->status,

        ]);

        return ServiceResponse::success('Restaurant timing update successful', ['restaurant_timing' => $restaurant_timing]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the restaurant by ID
        $RestaurantTiming = RestaurantTiming::find($id);

        // If the RestaurantTiming doesn't exist, return an error response
        if (!$RestaurantTiming) {
            return ServiceResponse::error("RestaurantTiming not found", 404);
        }

        // Delete the RestaurantTiming
        $RestaurantTiming->delete();

        // Return a success response
        return ServiceResponse::success("RestaurantTiming deleted successfully.");
    }
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|exists:restaurant_timings,id',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $ids = $request->input('ids', []);
        RestaurantTiming::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }
}
