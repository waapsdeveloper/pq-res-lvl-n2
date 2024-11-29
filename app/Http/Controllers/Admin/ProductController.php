<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        $query = Product::query();

        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new ProductResource($item);
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
    public function store(Request $request)
    {
        //
        //
        $data = $request->all();

        // Validate the required fields
        $validation = Validator::make($data, [
            'name' => 'required|string|min:3|max:255',
            'category' => 'nullable|integer|exists:categories,id', // Ensure role is provided
            'description' => 'nullable|string', // Ensure role is provided
            'price' => 'required|integer', // Ensure role is provided
            'status' => 'required|string|in:active,inactive', // Validate status
        ]);

        // If validation fails
        if ($validation->fails()) {
            return self::failure($validation->errors()->first());
        }

        // Create a new user (assuming the user model exists)
        $user = Product::create([
            'name' => $data['name'],
            'category_id' => $data['category'] ?? 0,
            'description' => $data['description'] ?? '',
            'price' => $data['price'],
            'status' => $data['status'],
        ]);

        return self::success('Category store successful', ['category' => $user]);
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