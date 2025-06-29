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
        $active_restaurant = Helper::getActiveRestaurantId();
        $resID = $request->restaurant_id == -1 ? $active_restaurant->id : $request->restaurant_id;

        $query = Category::query()->where('restaurant_id', $resID)->orderBy('id', 'desc');
        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($filters) {

            $filters = json_decode($filters, true); // Decode JSON to array

            if (isset($filters['name']) && !empty($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (isset($filters['status']) && !empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (isset($filters['restaurant_id']) && !empty($filters['restaurant_id'])) {
                $query->where('restaurant_id', $filters['restaurant_id']);
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
        if ($request->has('image')) {
            $image = $request->input('image');
            $fileSize = strlen($image) * 3 / 4; // Approximate size in bytes
            if ($fileSize > 3 * 1024 * 1024) {
                return response()
                    ->json(ServiceResponse::error('Image size exceeds 3 MB.'))
                    ->setStatusCode(422);
            }
        }
        //
        // $data = $request->all();
        $data = $request->validated();
        // return response()->json($data);
        // Create a new user (assuming the user model exists)
        $category = Category::create([
            'name' => $data['name'],
            'identifier' => null,
            'category_id' => $data['category_id'] ?? null,
            'restaurant_id' => $data['restaurant_id'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
        ]);

        $identifier = Identifier::make('Category', $category->id, 3);
        $category->update(['identifier' => $identifier]);

        if (isset($data['image'])) {
            // Check if it's a base64 image or a URL
            if (strpos($data['image'], 'data:image') === 0) {
                // It's a base64 image
                $url = Helper::getBase64ImageUrl($data['image'], 'category');
                $category->update(['image' => $url]);
            } else {
                // It's already a URL, use it directly
                $category->update(['image' => $data['image']]);
            }
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
        if ($request->has('image')) {
            $image = $request->input('image');
            $fileSize = strlen($image) * 3 / 4; // Approximate size in bytes
            if ($fileSize > 3 * 1024 * 1024) {
                return response()
                    ->json(ServiceResponse::error('Image size exceeds 3 MB.'))
                    ->setStatusCode(422);
            }
        }
        // dd($request->validated());
        $data = $request->validated();

        // dd($data['image']);
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
            
            // Check if it's a base64 image or a URL
            if (strpos($data['image'], 'data:image') === 0) {
                // It's a base64 image
                $url = Helper::getBase64ImageUrl($data['image'], 'category');
                $data['image'] = $url;
            }
            // If it's already a URL, use it directly (no change needed)
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
            'ids.*' => 'required|exists:restaurant_timings_meta,id',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $ids = $request->input('ids', []);
        Category::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }

    /**
     * Upload category image
     */
    public function uploadImage(Request $request, string $id)
    {
        // Validate that the category exists
        $category = Category::find($id);
        if (!$category) {
            return ServiceResponse::error('Category not found', [], 404);
        }

        // Validate that an image file was uploaded
        $validator = Validator::make($request->all(), [
            'image' => 'required|file|image|mimes:jpeg,png,jpg,webp|max:1024', // 1MB max
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Image validation failed', $validator->errors());
        }

        try {
            // Upload the image
            $url = Helper::uploadFile($request->file('image'), 'category');
            
            if ($url) {
                // Update the category with the new image
                $category->update(['image' => $url]);
                
                return ServiceResponse::success('Image uploaded successfully', [
                    'image_url' => $url,
                    'full_url' => Helper::returnFullImageUrl($url),
                    'category_id' => $id
                ]);
            } else {
                return ServiceResponse::error('Failed to upload image');
            }
        } catch (\Exception $e) {
            return ServiceResponse::error('Image upload failed: ' . $e->getMessage());
        }
    }
}
