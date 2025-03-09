<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coupon\StoreCoupon;
use App\Http\Requests\Admin\Coupon\UpdateCoupon;
use App\Http\Resources\Admin\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            return new CouponResource($item);
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
    public function store(StoreCoupon $request)
    {
        //
        // $data = $request->all();
        $data = $request->validated();
        // return response()->json($data);
        // Create a new user (assuming the user model exists)
        $coupon = Coupon::create([
            'code' => $data['code'],
            'discount_value' => $data['discount_value'],
            'discount_type' => $data['discount_type'],
            'usage_limit' => $data['usage_limit'] ?? null,
            'used_count' => $data['used_count'] ?? 0,
            'expires_at' => $data['expires_at'] ?? null,
            'is_active' => $data['is_active'],
            // 'restaurant_id' => $data['restaurant_id'] ?? null, // If applicable
        ]);

        return ServiceResponse::success('Category store successful', ['coupon' => $coupon]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        // Attempt to find the restaurant by ID
        $coupon = Coupon::find($id);


        // If the category doesn't exist, return an error response
        if (!$coupon) {
            return ServiceResponse::error("Coupon not found", 404);
        }
        $data = new CouponResource($coupon);
        // Return a success response with the category data
        return ServiceResponse::success("Coupon details retrieved successfully", ['coupon' => $data]);
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
    public function update(UpdateCoupon $request, string $id)
    {
        //
        // dd($request->validated());
        $data = $request->validated();

        // dd($data['image']);
        // Find the category by ID
        $coupon = Coupon::find($id);

        // If coupon does not exist
        if (!$coupon) {
            return ServiceResponse::error('coupon not found');
        }


        $coupon->update([
            'code' => $data['code'] ?? $coupon->code,
            'discount_value' => $data['discount_value'] ?? $coupon->discount_value,
            'discount_type' => $data['discount_type'] ?? $coupon->discount_type,
            'usage_limit' => $data['usage_limit'] ?? $coupon->usage_limit,
            'used_count' => $data['used_count'] ?? $coupon->used_count,
            'expires_at' => $data['expires_at'] ?? $coupon->expires_at,
            'is_active' => $data['is_active'] ?? $coupon->is_active,
            // 'restaurant_id' => $data['restaurant_id'] ?? $coupon->restaurant_id, // If applicable
        ]);

        return ServiceResponse::success('Coupon updated successfully', ['coupon' => $coupon]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the coupon by ID
        $coupon = Coupon::find($id);

        // If the coupon doesn't exist, return an error response
        if (!$coupon) {
            return ServiceResponse::error("Coupon $id not found", 404);
        }

        // Delete the coupon
        $coupon->delete();

        // Return a success response
        return ServiceResponse::success("Coupon deleted successfully.");
    }

    /**
     * Bulk delete coupons.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|exists:coupons,id',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $ids = $request->input('ids', []);
        Coupon::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }
}
