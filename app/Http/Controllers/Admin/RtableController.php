<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Rtable\StoreRtable;
use App\Http\Requests\Admin\Rtable\UpdateRtable;
use App\Http\Resources\Admin\RtableResource;
use App\Models\Rtable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RtableController extends Controller
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

        $query = Rtable::query()->with('restaurantDetail', 'restaurantTimings');

        // Optionally apply search filter if needed
        if ($search) {
            $query->where('identifier', 'like', '%' . $search . '%');
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
            return new RtableResource($item);
        });

        // Return the response with image URLs included
        return self::success("Category list successfully", ['data' => $data]);
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
    public function store(StoreRtable $request)
    {
        //
        // $data = $request->all();
        $data = $request->validated();
        // Create a new user (assuming the user model exists)
        $item = Rtable::create([
            'restaurant_id' => $data['restaurant_id'],
            'identifier' => $data['identifier'],
            'no_of_seats' => $data['no_of_seats'],
            'floor' => $data['floor'],
            'status' => $data['status'],
            'description' => $data['description'] ?? null, // Default to null if not provided
        ]);

        return self::success('Rtable store successful', ['item' => $item]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        // Attempt to find the restaurant by ID
        $restaurant = Rtable::find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return self::failure("Rtable not found", 404);
        }

        // Return a success response with the restaurant data
        return self::success("Rtable details retrieved successfully", ['Rtable' => $restaurant]);
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
    public function update(UpdateRtable $request, $id)
    {
        $data = $request->validated();

        // Find the Rtable
        $rtable = Rtable::find($id);
        // dd($data, $rtable);
        if (!$rtable) {
            return self::failure("Rtable with ID $id not found.");
        }

        // Update the Rtable details
        $rtable->update([
            'restaurant_id' => $data['restaurant_id'] ?? $rtable->restaurant_id,
            'identifier' => $data['identifier'] ?? $rtable->identifier,
            'no_of_seats' => $data['no_of_seats'] ?? $rtable->no_of_seats,
            'floor' => $data['floor'] ?? $rtable->floor,
            'status' => $data['status'] ?? $rtable->status,
            'description' => $data['description'] ?? $rtable->description,
        ]);

        return self::success('Rtable updated successfully', ['item' => $rtable]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the restaurant by ID
        $restaurant = Rtable::find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return self::failure("user not found", 404);
        }

        // Delete the restaurant
        $restaurant->delete();

        // Return a success response
        return self::success("User deleted successfully.");
    }
}
