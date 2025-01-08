<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\VariationResource;
use App\Models\Variation;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Variation\StoreVariation;
use App\Http\Requests\Admin\Variation\UpdateVariation;
use Illuminate\Support\Facades\Validator;


class VariationController extends Controller
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

        $query = Variation::query()->orderBy('id', 'desc');

        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($filters) {

            $filters = json_decode($filters, true); // Decode JSON to array

            if (isset($filters['name']) && !empty($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            // if (isset($filters['status']) && !empty($filters['status'])) {
            //     $query->where('status', $filters['status']);
            // }
            // if (isset($filters['restaurant_id']) && !empty($filters['restaurant_id'])) {
            //     $query->where('restaurant_id', $filters['restaurant_id']);
            // }
        }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new VariationResource($item);
        });

        // Return the response with image URLs included
        return ServiceResponse::success("Variation list successfully", ['data' => $data]);
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
    public function store(StoreVariation $request)
    {
        // $data = $request->all();
        $data = $request->validated();
        $variation = Variation::create([
            'name' => $data['name'],
            'meta_value' => json_encode($data['meta_value']),
            'description' => $data['description'],
        ]);

        return ServiceResponse::success('Variation store successful', ['variation' => $variation]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $Variation = Variation::find($id);


        // If the Variation doesn't exist, return an error response
        if (!$Variation) {
            return ServiceResponse::error("Variation not found", 404);
        }
        $data = new VariationResource($Variation);
        // Return a success response with the Variation data
        return ServiceResponse::success("Variation details retrieved successfully", ['variation' => $data]);
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
    public function update(UpdateVariation $request, string $id)
    {
        // dd($request->validated());
        $data = $request->validated();

        // dd($data['image']);
        // Find the variation by ID
        $variation = Variation::find($id);

        // If variation does not exist
        if (!$variation) {
            return ServiceResponse::error('variation not found');
        }


        $variation->update([
            'name' => $data['name'] ?? $variation->name,
            'meta_value' => json_encode($data['meta_value']) ?? $variation->meta_value,
            'description' => $data['description'] ?? $variation->description,

        ]);

        return ServiceResponse::success('Variation updated successfully', ['variation' => $variation]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the restaurant by ID
        $variation = Variation::find($id);

        // If the variation doesn't exist, return an error response
        if (!$variation) {
            return ServiceResponse::error("variation $id not found", 404);
        }

        // Delete the variation
        $variation->delete();

        // Return a success response
        return ServiceResponse::success("variation deleted successfully.");
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
        variation::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }
}
