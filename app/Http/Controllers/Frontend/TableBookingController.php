<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\CheckAvailabilityResource;
use App\Http\Resources\Frontend\TableBookingResource;
use App\Models\RTable;
use App\Models\RTableBooking_RTable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class TableBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return ServiceResponse::success('Table availability', ['data' => $rtables]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);

        $query = RTable::query()->where('status', 'active')->with('restaurant', 'restaurantTimings');

        // dd($query);
        // Optionally apply search filter if needed
        if ($search) {
            $query->where('identifier', 'like', '%' . $search . '%');
        }

        if ($filters) {
            $filters = json_decode($filters, true); // Decode JSON string into an associative array

            if (isset($filters['identifier'])) {
                $query->where('identifier', 'like', '%' . $filters['identifier'] . '%');
            }
            if (isset($filters['restaurant'])) {
                $query->where('restaurant', 'like', '%' . $filters['restaurant'] . '%');
            }

            if (isset($filters['days'])) {
                $query->where('days', $filters['days']);
            }
            if (isset($filters['date'])) {
                $query->where('date', $filters['date']);
            }
            if (isset($filters['time'])) {
                $query->where('time', $filters['time']);
            }
        }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new TableBookingResource($item);
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
    public function store(Request $request)
    {
        //
        //
        $data = $request->all();
        // $data = $request->validated();

        // Validate the required fields
        $validation = Validator::make($data, [
            'no_of_seats' => 'required|number|min:2|max:10',
            'date' => 'required|integer|exists:categories,id', // Ensure role is provided
            'time' => 'required|string|in:active,inactive', // Validate status
        ]);

        // // If validation fails
        // if ($validation->fails()) {
        //     return self::failure($validation->errors()->first());
        // }

        // Create a new user (assuming the user model exists)
        // $user = Category::create([
        //     'name' => $data['name'],
        //     'category_id' => $data['category'] ?? 0,
        //     'status' => $data['status'],
        // ]);

        // return ServiceResponse::success('Category store successful', ['Category' => $user]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

    public function checkTableAvailability(Request $request)
    {
        // Fetch available tables with eager loaded relationships
        $availableTables = RTableBooking_RTable::with('restaurant.timings')->get();

        // Check if records exist
        if ($availableTables->isEmpty()) {
            return ServiceResponse::error('No tables available for the selected criteria.');
        }

        // Transform available tables into resources for the response
        $data = $availableTables->map(function ($item) {
            return [
                'id' => $item->id,
                'restaurant_id' => $item->restaurant_id,
                'rtable_booking_id' => $item->rtable_booking_id,
                'rtable_id' => $item->rtable_id,
                'booking_start' => $item->booking_start,
                'booking_end' => $item->booking_end,
                'no_of_seats' => $item->no_of_seats,
                'restaurant' => [
                    'id' => $item->restaurant->id,
                    'name' => $item->restaurant->name,
                    'timings' => $item->restaurant->timings, // Include restaurant timings directly
                ],
            ];
        });

        return ServiceResponse::success('Table availability', ['data' => $data]);
    }
}
