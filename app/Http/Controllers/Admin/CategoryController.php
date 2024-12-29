<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\Identifier;
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
        $filters = $request->input('filters', null);

        $query = Category::query()->orderBy('id', 'desc');

        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($filters) {
            $filters = json_decode($filters, true); // Decode JSON string into an associative array

            if (isset($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }
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
    public function store(StoreCategory $request)
    {
        //
        // $data = $request->all();
        $data = $request->validated();
        // return response()->json($data);
        // Create a new user (assuming the user model exists)
        $category = Category::create([
            'name' => $data['name'],
            'identifier' => null,
            'category_id' => $data['category_id'],
            'restaurant_id' => $data['restaurant_id'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'status' => $data['status'],
        ]);

        $identifier = Identifier::make('Category', $category->id, 3);
        $category->update(['identifier' => $identifier]);

        if (isset($data['image'])) {

            $url = Helper::getBase64ImageUrl($data['image'], 'category'); // Assuming a helper to handle the image upload
            $category->update(['image' => $url]);
        }

        return ServiceResponse::success('Category store successful', ['Category' => $category]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        // Attempt to find the restaurant by ID
        $category = Category::with('restaurant')->find($id);


        // If the category doesn't exist, return an error response
        if (!$category) {
            return ServiceResponse::error("Category not found", 404);
        }
        $data = new CategoryResource($category);
        // Return a success response with the category data
        return ServiceResponse::success("Category details retrieved successfully", ['category' => $data]);
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
        // dd($request->validated());
        $data = $request->validated();

        // Find the category by ID
        $category = Category::find($id);

        // If category does not exist
        if (!$category) {
            return ServiceResponse::error('Category not found');
        }

        $identifier = $data['identifier'] ?? Identifier::make('Category', $category->id, 3);

        if (isset($data['image'])) {
            if ($category->image) {
                Helper::deleteImage($category->image);
            }
            $url = Helper::getBase64ImageUrl($data['image'], 'category');
            $data['image'] = $url;
        }
        $category->update([
            'name' => $data['name'] ?? $category->name,
            'identifier' => $identifier,
            'category_id' => $data['category_id'] ?? $category->category_id,
            'restaurant_id' => $data['restaurant_id'] ?? $category->restaurant_id,
            'description' => $data['description'] ?? $category->description,
            'image' => $data['image'] ?? $category->image,
            'status' => $data['status'] ?? $category->status,
        ]);

        return ServiceResponse::success('Category updated successfully', ['category' => $category]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the restaurant by ID
        $category = Category::find($id);

        // If the category doesn't exist, return an error response
        if (!$category) {
            return ServiceResponse::error("Category $id not found", 404);
        }

        // Delete the category
        $category->delete();

        // Return a success response
        return ServiceResponse::success("Category deleted successfully.");
    }
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|exists:restaurant_timings,id',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $ids = $request->input('ids', []);
        Category::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }
}
