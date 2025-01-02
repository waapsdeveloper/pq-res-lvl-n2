<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
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

        $query = Invoice::query()->orderBy('id', 'desc');

        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // if ($filters) {

        //     $filters = json_decode($filters, true); // Decode JSON to array

        //     if (isset($filters['name']) && !empty($filters['name'])) {
        //         $query->where('name', 'like', '%' . $filters['name'] . '%');
        //     }

        //     if (isset($filters['status']) && !empty($filters['status'])) {
        //         $query->where('status', $filters['status']);
        //     }
        //     if (isset($filters['restaurant_id']) && !empty($filters['restaurant_id'])) {
        //         $query->where('restaurant_id', $filters['restaurant_id']);
        //     }
        // }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new InvoiceResource($item);
        });

        // Return the response with image URLs included
        return ServiceResponse::success("Invoices retrived successfully", ['data' => $data]);
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
    public function store(Request $request, $id)
    {
        // Fetch the order with its related products and restaurant
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return ServiceResponse::error('Invoice not found');
        }
        // Transform the order using OrderResource
        $data = new InvoiceResource($invoice);
        // Add the products to the resource

        return ServiceResponse::success('Invoice details fetched successfully', [
            'invoice' => $data,
        ]);
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
