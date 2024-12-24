<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Identifier;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Rtable\StoreRtable;
use App\Http\Requests\Admin\Rtable\UpdateRtable;
use App\Http\Resources\Admin\RtableResource;
use App\Models\Rtable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ServiceResponse;


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
            // return response()->json($filters);
            if ((isset($filters['Table No']) && !empty($filters['Table No'])) || (isset($filters['tableNo']) && !empty($filters['tableNo']))) {
                $query->where('identifier', 'like', '%' . ($filters['Table No'] ?? $filters['tableNo']) . '%');
            }

            if (isset($filters['address']) && !empty($filters['address'])) {
                $query->whereHas('restaurantDetail', function ($query) use ($filters) {
                    $query->where('address_line', 'like', '%' . $filters['address'] . '%');
                });
            }

            if (isset($filters['status']) && !empty($filters['status'])) {
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
        return ServiceResponse::success("Category list successfully", ['data' => $data]);
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
        $table = Rtable::create([
            'restaurant_id' => $data['restaurant_id'] ?? 1,
            'name' => $data['name'] ?? null,
            'identifier' => $data['identifier'] ?? "TBL",
            'no_of_seats' => $data['no_of_seats'] ?? 0,
            'floor' => $data['floor'] ?? null,
            'status' => $data['status'],
            'description' => $data['description'] ?? null, // Default to null if not provided
        ]);

        $identifier = Identifier::make('Table', $table->id, 5);
        $table->update(['identifier' =>  $identifier]);
        if ($table->name == null) {
            $table->update(['name' => $identifier]);
        }

        return ServiceResponse::success('Rtable store successful', ['item' => $table]);
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
            return ServiceResponse::error("Rtable not found", 404);
        }

        // Return a success response with the restaurant data
        return ServiceResponse::success("Rtable details retrieved successfully", ['Rtable' => $restaurant]);
    }

    public function getByRestaurantId(string $id)
    {
        // Fetch and group the data as before
        $restaurants = Rtable::with('restaurant:id,name')
            ->where('restaurant_id', $id)
            ->select('id', 'restaurant_id', 'floor', 'identifier', 'no_of_seats')
            ->get()
            ->groupBy('restaurant_id');

        // Get the first (and only) restaurant data
        $restaurantData = $restaurants->first();

        // Collect floor values into an array of strings
        $floors = $restaurantData->pluck('floor')->unique()->values()->toArray();

        // Return the tables and floors together

        return ServiceResponse::success('success', [
            'restaurant' => $restaurants->first(),
            'floors' => $floors
        ]);
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
            return ServiceResponse::error("Rtable with ID $id not found.");
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

        return ServiceResponse::success('Rtable updated successfully', ['item' => $rtable]);
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
            return ServiceResponse::error("user not found", 404);
        }

        // Delete the restaurant
        $restaurant->delete();

        // Return a success response
        return ServiceResponse::success("User deleted successfully.");
    }
}
