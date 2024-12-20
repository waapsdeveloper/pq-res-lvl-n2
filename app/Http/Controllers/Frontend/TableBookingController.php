<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\CheckAvailabilityResource;
use App\Http\Resources\Frontend\TableBookingResource;
use App\Models\RTable;
use App\Models\RTableBooking_RTable;
use App\Models\RTablesBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class TableBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     // return ServiceResponse::success('Table availability', ['data' => $rtables]);

    //     $search = $request->input('search', '');
    //     $page = $request->input('page', 1);
    //     $perpage = $request->input('perpage', 10);
    //     $filters = $request->input('filters', null);

    //     $query = RTable::query()->where('status', 'active')->with('restaurant', 'restaurantTimings');

    //     // dd($query);
    //     // Optionally apply search filter if needed
    //     if ($search) {
    //         $query->where('identifier', 'like', '%' . $search . '%');
    //     }

    //     if ($filters) {
    //         $filters = json_decode($filters, true); // Decode JSON string into an associative array

    //         if (isset($filters['identifier'])) {
    //             $query->where('identifier', 'like', '%' . $filters['identifier'] . '%');
    //         }
    //         if (isset($filters['restaurant'])) {
    //             $query->where('restaurant', 'like', '%' . $filters['restaurant'] . '%');
    //         }

    //         if (isset($filters['days'])) {
    //             $query->where('days', $filters['days']);
    //         }
    //         if (isset($filters['date'])) {
    //             $query->where('date', $filters['date']);
    //         }
    //         if (isset($filters['time'])) {
    //             $query->where('time', $filters['time']);
    //         }
    //     }

    //     // Paginate the results
    //     $data = $query->paginate($perpage, ['*'], 'page', $page);

    //     // Loop through the results and generate full URL for image
    //     $data->getCollection()->transform(function ($item) {
    //         return new TableBookingResource($item);
    //     });

    //     // Return the response with image URLs included
    //     return self::success("Trial list successfully", ['data' => $data]);
    // }

    public function index()
    {
        $bookings = RTablesBooking::where('customer_id', 1)
            ->with('rTable')
            ->get();

        return self::success(
            'Bookings fetched successfully.',
            [
                $bookings
            ]
        );
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function show()
    {

        dd("AGDg");
        // Fetch all bookings associated with this customer
        $bookings = RTablesBooking::where('customer_id', 1)
            ->with('rTableBookings')  // Eager load related rTableBookings
            ->get();

        // If there are no bookings, return an empty result
        if ($bookings->isEmpty()) {
            return self::failure('No bookings found for this customer.');
        }

        // Format the bookings for the response
        $formattedBookings = $bookings->map(function ($booking) {
            return [
                'booking_id' => $booking->id,
                'restaurant_id' => $booking->restaurant_id,
                'no_of_seats' => $booking->no_of_seats,
                'booking_start' => $booking->booking_start,
                'booking_end' => $booking->booking_end,
                'status' => $booking->status,
                'tables' => $booking->rTableBookings->map(function ($rTableBooking) {
                    return $rTableBooking->rtable_id;
                }),
            ];
        });

        return self::success([
            'message' => 'Bookings fetched successfully.',
            'result' => $formattedBookings
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // Add validation rules for start_time and end_time
        $validation = Validator::make($data, [
            'restaurant_id' => 'required|exists:restaurants,id',
            'no_of_seats' => 'required|integer|min:2|max:10',
            'tables' => 'required|array',
            'tables.*' => 'exists:rtables,id',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
        ]);

        if ($validation->fails()) {
            return self::failure($validation->errors()->first());
        }

        $restaurant_id = $data['restaurant_id'];
        $selected_tables = $data['tables'];
        $start_time = $data['start_time'];
        $end_time = $data['end_time'];
        $no_of_seats = $data['no_of_seats'];

        $booking = RTablesBooking::create([
            'customer_id' => 1,
            'restaurant_id' => $restaurant_id,
            'no_of_seats' => $no_of_seats,
            'booking_start' => $start_time,
            'booking_end' => $end_time,
            'description' => 'Table booking for ' . $restaurant_id,
            'status' => 'pending',
        ]);

        $booked_tables = [];
        foreach ($selected_tables as $table_id) {
            RTableBooking_RTable::create([

                'restaurant_id' => $restaurant_id,
                'rtable_id' => $table_id,
                'rtable_booking_id' => $booking->id,
                'booking_start' => $start_time,
                'booking_end' => $end_time,
            ]);
            $booked_tables[] = $table_id;
        };
            $result = [
                'booking_id' => $booking->id,
                'restaurant_id' => $restaurant_id,
                'tables' => $booked_tables,
                'booking_start' => $start_time,
                'booking_end' => $end_time,
                'status' => $booking->status,
        ];
        return self::success('Tables successfully booked for the restaurant',[$result]
        );
    }



    public function createBookingId()
    {
        // Generate a unique string using the current timestamp and a random string
        $timestamp = Carbon::now()->timestamp;  // Get the current timestamp
        $randomString = strtoupper(bin2hex(random_bytes(4)));  // Random string (8 characters)

        // Combine timestamp and random string to generate a unique booking ID
        return 'BID-' . $timestamp . '-' . $randomString;
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

    public function checkTableAvailability(Request $request, $id)
    {
        // $search = $request->input('search', '');



        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);
        // $restaurantId = $request->input('restaurant_id', null);
        // Fetch available tables with eager loaded relationships
        $query = RTableBooking_RTable::query()->with('restaurant.timings')
            ->where('restaurant_id', $id);
        // if ($search) {
        //     $query->where('identifier', 'like', '%' . $search . '%');
        // }

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
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        $data->getCollection()->transform(function ($item) {
            return new CheckAvailabilityResource($item);
        });

        // Transform available tables into resources for the response


        return ServiceResponse::success('Table availability', ['data' => $data]);
    }
}
