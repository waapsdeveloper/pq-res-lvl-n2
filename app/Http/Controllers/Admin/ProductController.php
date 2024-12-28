<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\Identifier;
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
        $filters = $request->input('filters', null);


        $query = Product::query();

        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }


        if ($filters) {
            $filters = json_decode($filters, true);
            if (isset($filters['name']) && !empty($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }
            if (isset($filters['category']) && !empty($filters['category'])) {
                // return response()->json($filters);
                $query->whereHas('category', function ($query) use ($filters) {
                    $query->where('name', 'like', '%' . $filters['category'] . '%');
                });
            }
            if (isset($filters['price']) && !empty($filters['price'])) {
                $query->where('price', '<=', $filters['price']);
            }
            if (isset($filters['discount']) && !empty($filters['discount'])) {
                $query->where('discount', "<=", $filters['discount']);
            }
            // if (isset($filters['is_today_deal'])) {
            //     $query->where('is_today_deal', 'like', '%' . $filters['is_today_deal'] . '%');
            // }
            // if (isset($filters['noOfOrders'])) {
            //     $query->where('noOfOrders', 'like', '%' . $filters['noOfOrders'] . '%');
            // }

            if (isset($filters['status']) && !empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
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
        dd($data);
        // Create a new user (assuming the user model exists)
        $product = Product::create([
            'name' => $data['name'],
            'category_id' => $data['category'] ?? 0,
            'restaurant_id' => $data['restaurant_id'] ?? null,
            'identifier' => "PROD",
            'description' => $data['description'] ?? '',
            'price' => $data['price'],
            'status' => $data['status'],
            'discount' => $data['discount'] ?? 0,
        ]);
        $identifier = Identifier::make('Product', $product->id, 4);
        $product->update(['identifier' => $identifier]);

        if (isset($data['image'])) {
            $url = Helper::getBase64ImageUrl($data['image'], 'product'); // Assuming a helper to handle the image upload
            $product->update(['image' => $url]);
        }

        return ServiceResponse::success('Product store successful', ['item' => $product]);
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
        $data = $request->validated();

        $product = Product::find($id);
        if (!$product) {
            return ServiceResponse::error('Product not found');
        }
        if (isset($data['image'])) {
            if ($product->image) {
                Helper::deleteImage($product->image);
            }
            $url = Helper::getBase64ImageUrl($data['image'], 'product');
            $data['image'] = $url;
        }
        $product->update([
            'name' => $data['name'] ?? $product->name,
            'category_id' => $data['category'] ?? $product->category_id,
            'restaurant_id' => $data['restaurant_id'] ?? $product->restaurant_id,
            'description' => $data['description'] ?? $product->description,
            'price' => $data['price'] ?? $product->price,
            'status' => $data['status'] ?? $product->status,
            'discount' => $data['discount'] ?? $product->discount,
            'image' => $data['image'] ?? $product->image,
        ]);

        $identifier = Identifier::make('Product', $product->id, 4);
        $product->update(['identifier' => $identifier]);




        return ServiceResponse::success('Product update successful', ['item' => $product]);
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
        Product::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }
}
