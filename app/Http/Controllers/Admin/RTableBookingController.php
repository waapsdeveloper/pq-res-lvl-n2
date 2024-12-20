<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RTablebooking\StoreRTablesBooking;
use App\Http\Requests\Admin\RTablebooking\UpdateRTableBooking;
use App\Http\Resources\Admin\RTableBookingResource;
use App\Models\RTableBooking_RTable;
use App\Models\RTablesBooking;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RTableBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perPage = $request->input('perpage', 10);
        $filters = $request->input('filters', '');


        $query = RtablesBooking::query();

        // Apply search filter if a search term is provided
        if ($search) {
            $query->where('booking_start', 'like', '%' . $search . '%');
        }
        if ($filters) {
            $filters = json_decode($filters, true); // Decode JSON string into an associative array

            if (isset($filters['customer_id'])) {
                $query->where('customer_id', 'like', '%' . $filters['customer_id'] . '%');
            }
            if (isset($filters['booking_start'])) {
                $query->where('booking_start', 'like', '%' . $filters['booking_start'] . '%');
            }

            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }
        }
        // Paginate the results
        $data = $query->paginate($perPage, ['*'], 'page', $page);

        // Transform the paginated collection with the resource
        $data->getCollection()->transform(function ($item) {
            return new RTableBookingResource($item);
        });

        // Return a successful response with the transformed data
        return self::success("Rtables booking list retrieved successfully", ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return the view or data needed for creating a new RtablesBooking
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRTablesBooking $request)
    {
        $data = $request->validated();

        // Save the main booking
        $booking = RtablesBooking::create([
            'customer_id' => $data['customer_id'],
            'order_id' => $data['order_id'] ?? null,
            'booking_start' => $data['booking_start'],
            'booking_end' => $data['booking_end'],
            'no_of_seats' => $data['no_of_seats'],
            'description' => $data['description'],
            'status' => $data['status'],
        ]);

        // Save associated rtable entries
        foreach ($data['rtable_id'] as $rtableId) {
            $rtableEntries[] = [
                'restaurant_id' => $data['restaurant_id'],
                'rtable_booking_id' => $booking->id,
                'rtable_id' => $rtableId,
                'booking_start' => $data['booking_start'],
                'booking_end' => $data['booking_end'],
                'no_of_seats' => $data['no_of_seats'],
            ];
        }

        RTableBooking_RTable::insert($rtableEntries);

        return ServiceResponse::success(
            'RtablesBooking store successful',
            ['booking' => $booking]
        );
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = RtablesBooking::find($id);

        // If the booking doesn't exist, return an error response
        if (!$booking) {
            return self::failure("RtablesBooking not found", 404);
        }

        // Return a success response with the booking data
        return self::success("RtablesBooking details retrieved successfully", ['booking' => $booking]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Return the view or data needed for editing an RtablesBooking
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRTableBooking $request, string $id)
    {
        $data = $request->validated();

        $booking = RtablesBooking::find($id);
        if (!$booking) {
            return self::failure('Rtables booking not found');
        }

        $booking->update([
            'customer_id' => $data['customer_id'] ?? $booking->customer_id,
            'order_id' => $data['order_id'] ?? $booking->order_id,
            'booking_start' =>  $data['booking_start'] ?? $booking->booking_start,
            'booking_end' =>  $data['booking_end'] ?? $booking->booking_end,
            'no_of_seats' => $data['no_of_seats'] ?? $booking->no_of_seats,
            'description' => $data['description'] ?? $booking->description,
            'status' => $data['status'] ?? $booking->status,
        ]);

        return ServiceResponse::success('Rtables booking update successful', ['booking' => $booking]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the booking by ID
        $booking = RTablesBooking::find($id);

        // If the booking doesn't exist, return an error response
        if (!$booking) {
            return self::failure("RtablesBooking not found", 404);
        }

        // Delete the booking
        $booking->delete();

        // Return a success response
        return self::success("RtablesBooking deleted successfully.");
    }
}