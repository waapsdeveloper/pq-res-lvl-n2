<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategory;
use App\Http\Requests\Admin\Category\UpdateCategory;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);

        $query = Category::query();

        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new CategoryResource($item);
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
    public function store(StoreCategory $request)
    {
        //
        // $data = $request->all();
        $data = $request->validated();

        // Validate the required fields
        // $validation = Validator::make($data, [
        //     'name' => 'required|string|min:3|max:255',
        //     'category' => 'nullable|integer|exists:categories,id', // Ensure role is provided
        //     'status' => 'required|string|in:active,inactive', // Validate status
        // ]);

        // // If validation fails
        // if ($validation->fails()) {
        //     return self::failure($validation->errors()->first());
        // }

        // Create a new user (assuming the user model exists)
        $user = Category::create([
            'name' => $data['name'],
            'category_id' => $data['category'] ?? 0,
            'status' => $data['status'],
        ]);

        return ServiceResponse::success('Category store successful', ['Category' => $user]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        // Attempt to find the restaurant by ID
        $restaurant = Category::find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return self::failure("Category not found", 404);
        }

        // Return a success response with the restaurant data
        return self::success("Category details retrieved successfully", ['category' => $restaurant]);
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
    public function update(UpdateCategory $request, string $id)
    {
        // Validate the incoming data
        // $data = $request->all();
        $data = $request->validated();

        // $validation = Validator::make($data, [
        //     'name' => 'required|string|min:3|max:255',
        //     'category' => 'nullable|integer|exists:categories,id', // Ensure category is valid
        //     'status' => 'required|string|in:active,inactive', // Validate status
        // ]);

        // If validation fails
        // if ($validation->fails()) {
        //     return self::failure($validation->errors()->first());
        // }

        // Find the category by ID
        $category = Category::find($id);

        // If category does not exist
        if (!$category) {
            return self::failure('Category not found');
        }

        // Update the category
        $category->update([
            'name' => $data['name'],
            'category_id' => $data['category'] ?? 0,
            'status' => $data['status'],
        ]);

        return ServiceResponse::success('Category update successful', ['Category' => $category]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the restaurant by ID
        $restaurant = Category::find($id);

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
