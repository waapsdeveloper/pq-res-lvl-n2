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
            $query->where('meta_key', 'like', '%' . $search . '%');
        }
        
        if ($filters) {
            if (is_string($filters)) {
                $filters = json_decode($filters, true);
            }
            if (is_array($filters)) {
                if (isset($filters['meta_key'])) {
                    $query->where('meta_key', 'like', '%' . $filters['meta_key'] . '%');
                }
                if (isset($filters['restaurant_id'])) {
                    $query->where('restaurant_id', 'like', '%' . $filters['restaurant_id'] . '%');
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
     * Get timing configuration for a restaurant
     */
    public function getTimingConfig(Request $request)
    {
        $restaurantId = $request->input('restaurant_id');
        
        if (!$restaurantId) {
            $active_restaurant = Helper::getActiveRestaurantId();
            $restaurantId = $active_restaurant->id;
        }

        $timingConfig = RestaurantTiming::getTimingConfig($restaurantId);
        
        // Format the data for frontend
        $formattedData = [];
        foreach (RestaurantTiming::getDayOptions() as $dayKey => $dayName) {
            $formattedData[$dayKey] = [
                'day_name' => $dayName,
                'start_time' => $timingConfig[$dayKey . '_start_time'] ?? null,
                'end_time' => $timingConfig[$dayKey . '_end_time'] ?? null,
                'break_start' => $timingConfig[$dayKey . '_break_start'] ?? null,
                'break_end' => $timingConfig[$dayKey . '_break_end'] ?? null,
                'is_24_hours' => (bool)($timingConfig[$dayKey . '_is_24_hours'] ?? false),
                'is_off' => (bool)($timingConfig[$dayKey . '_is_off'] ?? false),
                'formatted_timing' => RestaurantTiming::getFormattedTiming($restaurantId, $dayKey)
            ];
        }

        // Add global settings
        $formattedData['same_time_all_days'] = (bool)($timingConfig['same_time_all_days'] ?? false);
        $formattedData['off_days'] = $timingConfig['off_days'] ?? [];

        return ServiceResponse::success("Restaurant timing configuration retrieved successfully", [
            'restaurant_id' => $restaurantId,
            'timing_config' => $formattedData
        ]);
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
        try {
            $data = $request->validated();
            $restaurantId = $data['restaurant_id'] ?? null;
            $timingData = $data['timing'] ?? null;

            if (!$restaurantId) {
                return ServiceResponse::error('Restaurant ID is required', 400);
            }

            if (!$timingData) {
                return ServiceResponse::error('Timing data is required', 400);
            }

            // Convert the new structure to config array
            $config = [];
            
            // Handle global settings
            if (isset($timingData['global'])) {
                $global = $timingData['global'];
                $config['global_start_time'] = $global['start_time'] ?? null;
                $config['global_end_time'] = $global['end_time'] ?? null;
                $config['global_day_type'] = $global['day_type'] ?? null;
                $config['global_is_24h'] = $global['is_24h'] ?? false;
                $config['global_break_times'] = json_encode($global['break_times'] ?? []);
            }

            // Handle days settings
            if (isset($timingData['days']) && is_array($timingData['days'])) {
                foreach ($timingData['days'] as $dayData) {
                    $day = strtolower($dayData['day']);
                    $config[$day . '_day'] = $dayData['day'];
                    $config[$day . '_start_time'] = $dayData['start_time'];
                    $config[$day . '_end_time'] = $dayData['end_time'];
                    $config[$day . '_status'] = $dayData['status'];
                    $config[$day . '_is_24h'] = $dayData['is_24h'];
                    $config[$day . '_is_open'] = $dayData['is_open'];
                    $config[$day . '_is_off_day'] = $dayData['is_off_day'];
                    $config[$day . '_break_times'] = json_encode($dayData['break_times'] ?? []);
                }
            }

            // Save timing configuration
            RestaurantTiming::setTimingConfig($restaurantId, $config);

            return ServiceResponse::success(
                'Restaurant timing configuration saved successfully',
                ['restaurant_id' => $restaurantId, 'config' => $config]
            );

        } catch (\Exception $e) {
            return ServiceResponse::error('Failed to save timing configuration: ' . $e->getMessage(), 500);
        }
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
        try {
            $data = $request->validated();
            $restaurantId = $data['restaurant_id'] ?? $id;
            $timingData = $data['timing'] ?? null;

            if (!$restaurantId) {
                return ServiceResponse::error('Restaurant ID is required', 400);
            }

            if (!$timingData) {
                return ServiceResponse::error('Timing data is required', 400);
            }

            // Convert the new structure to config array
            $config = [];
            
            // Handle global settings
            if (isset($timingData['global'])) {
                $global = $timingData['global'];
                $config['global_start_time'] = $global['start_time'] ?? null;
                $config['global_end_time'] = $global['end_time'] ?? null;
                $config['global_day_type'] = $global['day_type'] ?? null;
                $config['global_is_24h'] = $global['is_24h'] ?? false;
                $config['global_break_times'] = json_encode($global['break_times'] ?? []);
            }

            // Handle days settings
            if (isset($timingData['days']) && is_array($timingData['days'])) {
                foreach ($timingData['days'] as $dayData) {
                    $day = strtolower($dayData['day']);
                    $config[$day . '_day'] = $dayData['day'];
                    $config[$day . '_start_time'] = $dayData['start_time'];
                    $config[$day . '_end_time'] = $dayData['end_time'];
                    $config[$day . '_status'] = $dayData['status'];
                    $config[$day . '_is_24h'] = $dayData['is_24h'];
                    $config[$day . '_is_open'] = $dayData['is_open'];
                    $config[$day . '_is_off_day'] = $dayData['is_off_day'];
                    $config[$day . '_break_times'] = json_encode($dayData['break_times'] ?? []);
                }
            }

            // Update timing configuration
            RestaurantTiming::setTimingConfig($restaurantId, $config);

            return ServiceResponse::success('Restaurant timing configuration updated successfully', [
                'restaurant_id' => $restaurantId, 
                'config' => $config
            ]);

        } catch (\Exception $e) {
            return ServiceResponse::error('Failed to update timing configuration: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the restaurant timing by ID
        $restaurantTiming = RestaurantTiming::find($id);

        // If the RestaurantTiming doesn't exist, return an error response
        if (!$restaurantTiming) {
            return ServiceResponse::error("RestaurantTiming not found", 404);
        }

        // Delete the RestaurantTiming
        $restaurantTiming->delete();

        // Return a success response
        return ServiceResponse::success("RestaurantTiming deleted successfully.");
    }

    /**
     * Bulk delete timings
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|exists:restaurant_timings_meta,id',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $ids = $request->input('ids', []);
        RestaurantTiming::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }

    /**
     * Check if restaurant is open at specific time
     */
    public function checkOpenStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|integer|exists:restaurants,id',
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'time' => 'nullable|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $restaurantId = $request->input('restaurant_id');
        $day = $request->input('day');
        $time = $request->input('time');

        $isOpen = RestaurantTiming::isOpenAt($restaurantId, $day, $time);
        $formattedTiming = RestaurantTiming::getFormattedTiming($restaurantId, $day);

        return ServiceResponse::success("Open status checked successfully", [
            'restaurant_id' => $restaurantId,
            'day' => $day,
            'time' => $time,
            'is_open' => $isOpen,
            'formatted_timing' => $formattedTiming
        ]);
    }

    /**
     * Get timing data in frontend format
     */
    public function getTimingData(Request $request)
    {
        try {
            $restaurantId = $request->input('restaurant_id');
            
            if (!$restaurantId) {
                $active_restaurant = Helper::getActiveRestaurantId();
                $restaurantId = $active_restaurant->id;
            }

            $timingConfig = RestaurantTiming::getTimingConfig($restaurantId);
            
            // Build global settings
            $global = [
                'start_time' => $timingConfig['global_start_time'] ?? '09:00',
                'end_time' => $timingConfig['global_end_time'] ?? '17:00',
                'day_type' => $timingConfig['global_day_type'] ?? 'week_days',
                'is_24h' => (bool)($timingConfig['global_is_24h'] ?? false),
                'break_times' => json_decode($timingConfig['global_break_times'] ?? '[]', true) ?: []
            ];

            // Build days data
            $days = [];
            $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            
            foreach ($dayNames as $dayName) {
                $dayKey = strtolower($dayName);
                $days[] = [
                    'day' => $dayName,
                    'start_time' => $timingConfig[$dayKey . '_start_time'] ?? '09:00',
                    'end_time' => $timingConfig[$dayKey . '_end_time'] ?? '17:00',
                    'status' => $timingConfig[$dayKey . '_status'] ?? 'active',
                    'is_24h' => (bool)($timingConfig[$dayKey . '_is_24h'] ?? false),
                    'is_open' => (bool)($timingConfig[$dayKey . '_is_open'] ?? true),
                    'is_off_day' => (bool)($timingConfig[$dayKey . '_is_off_day'] ?? false),
                    'break_times' => json_decode($timingConfig[$dayKey . '_break_times'] ?? '[]', true) ?: []
                ];
            }

            $response = [
                'timing' => [
                    'global' => $global,
                    'days' => $days
                ],
                'restaurant_id' => $restaurantId
            ];

            return ServiceResponse::success("Restaurant timing data retrieved successfully", $response);

        } catch (\Exception $e) {
            return ServiceResponse::error('Failed to retrieve timing data: ' . $e->getMessage(), 500);
        }
    }
}
