<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);
        // $active_restaurant = Helper::getActiveRestaurantId();
        // $resID = $request->restaurant_id == -1 ? $active_restaurant->id : $request->restaurant_id;

        // ->where('restaurant_id', $resID)
        $query = Coupon::query()->orderBy('id', 'desc');
        // Optionally apply search filter if needed
        if ($search) {
            $query->where('code', 'like', '%' . $search . '%');
        }

        if ($filters) {

            $filters = json_decode($filters, true); // Decode JSON to array

            if (isset($filters['code']) && !empty($filters['code'])) {
                $query->where('code', 'like', '%' . $filters['code'] . '%');
            }

            if (isset($filters['status']) && !empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            // if (isset($filters['restaurant_id']) && !empty($filters['restaurant_id'])) {
            //     $query->where('restaurant_id', $filters['restaurant_id']);
            // }
        }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new CategoryResource($item);
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
