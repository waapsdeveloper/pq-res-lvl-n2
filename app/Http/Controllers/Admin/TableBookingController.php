<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\TableBookingResource;
use App\Models\RTablesBooking;


class TableBookingController extends Controller
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

        $query = RTablesBooking::query();

        // Optionally apply search filter if needed
        // if ($search) {
        //     $query->where('identifier', 'like', '%' . $search . '%');
        // }
        // if ($filters) {
        //     $filters = json_decode($filters, true); // Decode JSON string into an associative array

        //     if (isset($filters['name'])) {
        //         $query->where('name', 'like', '%' . $filters['name'] . '%');
        //     }

        //     if (isset($filters['address'])) {
        //         $query->where('address', 'like', '%' . $filters['address'] . '%');
        //     }

        //     if (isset($filters['status'])) {
        //         $query->where('status', $filters['status']);
        //     }
        // }
        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new TableBookingResource($item);
        });

        // Return the response with image URLs included
        return self::success("Table Booking list successfully", ['data' => $data]);
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
}
