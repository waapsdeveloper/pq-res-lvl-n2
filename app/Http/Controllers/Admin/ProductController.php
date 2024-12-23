<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProduct;
use App\Http\Requests\Admin\Product\UpdateProduct;
use App\Http\Resources\Admin\CategoryResource;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);

        $category = $request->input('category_id', '');

        $query = Product::query();

        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new ProductResource($item);
        });

        // Return the response with image URLs included
        return ServiceResponse::success("Product list successfully", ['data' => $data]);
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
    public function store(StoreProduct $request)
    {
        //
        // $data = $request->all();
        $data = $request->validated();

        // Validate the required fields
        // $validation = Validator::make($data, [
        //     'name' => 'required|string|min:3|max:255',
        //     'category' => 'nullable|integer|exists:categories,id', // Ensure role is provided
        //     'description' => 'nullable|string', // Ensure role is provided
        //     'price' => 'required|integer', // Ensure role is provided
        //     'status' => 'required|string|in:active,inactive', // Validate status
        // ]);

        // // If validation fails
        // if ($validation->fails()) {
        //     return ServiceResponse::error($validation->errors()->first());
        // }

        // Create a new user (assuming the user model exists)
        $item = Product::create([
            'name' => $data['name'],
            'category_id' => $data['category'] ?? 0,
            'description' => $data['description'] ?? '',
            'price' => $data['price'],
            'status' => $data['status'],
        ]);

        return ServiceResponse::success('Product store successful', ['item' => $item]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        // Attempt to find the restaurant by ID
        $restaurant = Product::find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return ServiceResponse::error("Product not found", 404);
        }

        // Return a success response with the restaurant data
        return ServiceResponse::success("Product details retrieved successfully", ['product' => $restaurant]);
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
    public function update(UpdateProduct $request, string $id)
    {

        // $data = $request->all();
        $data = $request->validated();


        // $validation = Validator::make($data, [
        //     'name' => 'required|string|min:3|max:255',
        //     'category' => 'nullable|integer|exists:categories,id', // Ensure category exists
        //     'description' => 'nullable|string', // Description is optional
        //     'price' => 'required|integer', // Price is required
        //     'status' => 'required|string|in:active,inactive', // Validate status
        // ]);

        // if ($validation->fails()) {
        //     return ServiceResponse::error($validation->errors()->first());
        // }

        $item = Product::find($id);
        if (!$item) {
            return ServiceResponse::error('Product not found');
        }

        $item->update([
            'name' => $data['name'],
            'category_id' => $data['category'] ?? $item->category_id, // Only update if provided
            'description' => $data['description'] ?? $item->description, // Only update if provided
            'price' => $data['price'],
            'status' => $data['status'],
        ]);

        return ServiceResponse::success('Product update successful', ['item' => $item]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the restaurant by ID
        $restaurant = Product::find($id);

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
