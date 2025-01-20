<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Admin\Invoice\UpdateInvoiceRequest;
use App\Http\Resources\Admin\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Support\Facades\Validator;
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

        $query = Invoice::query()
            ->orderBy('id', 'desc');

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
    public function store(StoreInvoiceRequest $request)
    {
        $data = $request->validated();
        // Create a new invoice
        $invoice = Invoice::create([
            'order_id' => $data['order_id'],
            'invoice_no' => $data['invoice_no'],
            'invoice_date' => $data['invoice_date'],
            'payment_method' => $data['payment_method'],
            'total' => $data['total'],
            'status' => $data['status'],
        ]);
        return ServiceResponse::success("Invoice details retrieved successfully", ['invoice' => $invoice]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = Invoice::with('order')->findOrFail($id);
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, string $id)
    {
        $data = $request->validated();
        $invoice = Invoice::findOrFail($id);
        // Create a new invoice
        $invoice->update([
            'order_id' => $data['order_id'],
            'invoice_no' => $data['invoice_no'],
            'invoice_date' => $data['invoice_date'],
            'payment_method' => $data['payment_method'],
            'total' => $data['total'],
            'status' => $data['status'],
        ]);
        return ServiceResponse::success("Invoice updated successfully", ['invoice' => $invoice]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        if (!$invoice) {
            return ServiceResponse::error("Invoice $id not found");
        }
        $invoice->delete();
        return ServiceResponse::success("Invoice deleted successfully", ['invoice' => $invoice]);
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
        Invoice::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }
}
