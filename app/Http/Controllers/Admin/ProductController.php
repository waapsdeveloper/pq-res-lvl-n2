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
use App\Models\ProductProps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $active_restaurant = Helper::getActiveRestaurantId();
        $resID = $request->restaurant_id == -1 ? $active_restaurant->id : $request->restaurant_id;

        $query = Product::query()
            ->where('restaurant_id', $resID)
            ->with('category', 'restaurant', 'productProps', 'variation')
            ->orderBy('created_at', 'desc');

        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Decode filters if it's a JSON string
        if ($filters) {
            $filters = json_decode($filters, true); // Decode JSON to array

            // Apply filters to query
            if (isset($filters['name']) && !empty($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (isset($filters['category_id']) && !empty($filters['category_id'])) {
                $query->where('category_id', $filters['category_id']);
            }

            if (isset($filters['price']) && !empty($filters['price'])) {
                $query->where('price', '<=', $filters['price']);
            }

            if (isset($filters['discount']) && !empty($filters['discount'])) {
                $query->where('discount', '<=', $filters['discount']);
            }

            if (isset($filters['status']) && !empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            // if (isset($filters['is_today_deal'])) {
            //     $query->where('is_today_deal', 'like', '%' . $filters['is_today_deal'] . '%');
            // }
            // if (isset($filters['noOfOrders'])) {
            //     $query->where('noOfOrders', 'like', '%' . $filters['noOfOrders'] . '%');
            // }
        }
        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Transform the results using ProductResource
        $data->getCollection()->transform(function ($item) {
            return new ProductResource($item);
        });

        // Return the response
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
        if ($request->has('image')) {
            $image = $request->input('image');
            $fileSize = strlen($image) * 3 / 4; // Approximate size in bytes
            if ($fileSize > 3 * 1024 * 1024) {
                return response()
                    ->json(ServiceResponse::error('Image size exceeds 3 MB.'))
                    ->setStatusCode(422);
            }
        }
        // Validate and get the data
        $data = $request->validated();

        // Create the Product instance
        $product = Product::create([
            'name' => $data['name'],
            'category_id' => $data['category_id'] ?? 0,
            'restaurant_id' => $data['restaurant_id'],
            'identifier' => "PROD",
            'description' => $data['description'] ?? '',
            'price' => $data['price'],
            'status' => $data['status'],
            'discount' => $data['discount'] ?? 0,
            // 'variation_id' => $data['variation_id'] ?? null,

        ]);

        // Generate and update identifier
        $identifier = Identifier::make('Product', $product->id, 4);
        $product->update(['identifier' => $identifier]);

        // Handle image upload
        if (isset($data['image'])) {
            $url = Helper::getBase64ImageUrl($data['image'], 'product');
            $product->update(['image' => $url]);
        }
        if (isset($data['variation']) && is_array($data['variation'])) {
            ProductProps::create([
                'product_id' => $product->id,
                'meta_key' => 'variation',
                'meta_value' => json_encode($data['variation']),
                'meta_key_type' => gettype($data['variation']),
            ]);
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
        $product = Product::with('category', 'restaurant', 'productProps', 'variation')
            ->find($id);


        // If the product doesn't exist, return an error response
        if (!$product) {
            return ServiceResponse::error("Product not found", 404);
        }

        $data = new ProductResource($product);
        // Return a success response with the restaurant data
        return ServiceResponse::success("Product details retrieved successfully", ['product' => $data]);
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
        if ($request->has('image')) {
            $image = $request->input('image');
            $fileSize = strlen($image) * 3 / 4; // Approximate size in bytes
            if ($fileSize > 3 * 1024 * 1024) {
                return response()
                    ->json(ServiceResponse::error('Image size exceeds 3 MB.'))
                    ->setStatusCode(422);
            }
        }
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
            // 'variation_id' => $data['variation_id'] ?? $product->variation_id,
            'image' => $data['image'] ?? $product->image,
        ]);

        $identifier = Identifier::make('Product', $product->id, 4);
        $product->update(['identifier' => $identifier]);



        ProductProps::where('product_id', $product->id)->delete();
        if (isset($data['variation']) && is_array($data['variation'])) {
            ProductProps::create([
                'product_id' => $product->id,
                'meta_key' => 'variation',
                'meta_value' => json_encode($data['variation']),
                'meta_key_type' => gettype($data['variation']),
            ]);
        }
        return ServiceResponse::success('Product update successful', ['item' => $product]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the restaurant by ID
        $product = Product::find($id);
        ProductProps::where('product_id', $product->id)->delete();

        if (!$product) {
            return ServiceResponse::error("Product not found", 404);
        }

        $product->delete();
        return ServiceResponse::success("{roduct deleted successfully.");
    }
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|exists:products,id',  // Ensure valid product IDs
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }
        $ids = $request->input('ids', []);
        ProductProps::whereIn('product_id', $ids)->delete();

        Product::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }

    public function bulkFetch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists',
            'ids.*' => 'required|exists:restaurants,id',  // Ensure valid restaurant IDs
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors()->first());
        }

        $query = Product::query();

        $query->where('restaurant_id', $request->input('restaurant_id'));

        $query->with('category', 'restaurant', 'productProps');
        $query->orderBy('created_at', 'desc');
        $data = $query->get();

        $data->getCollection()->transform(function ($item) {
            return new ProductResource($item);
        });

        return ServiceResponse::success("Bulk Fetch successful", ['data' => $data]);
    }
}
