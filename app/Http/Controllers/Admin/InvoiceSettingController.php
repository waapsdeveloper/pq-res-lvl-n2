<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InvoiceSetting\StoreInvoiceSetting;
use App\Http\Requests\Admin\InvoiceSetting\UpdateInvoiceSetting;
use App\Models\InvoiceSetting;
use App\Models\Restaurant;
use App\Http\Resources\Admin\InvoiceResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);

        $query = InvoiceSetting::with('restaurant')->orderByDesc('id');

        if ($search) {
            $query->where('footer_text', 'like', '%' . $search . '%')
                ->orWhere('restaurant_address', 'like', '%' . $search . '%');
        }

        $data = $query->paginate($perpage, ['*'], 'page', $page);

        return ServiceResponse::success("Invoice settings list", ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */

    // POST create
    public function store(Request $request)
    {
        $data = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'invoice_logo' => 'nullable|string',
            'size' => 'required|string',
            'left_margin' => 'nullable|integer',
            'right_margin' => 'nullable|integer',
            'google_review_barcode' => 'nullable|string',
            'footer_text' => 'nullable|string',
            'restaurant_address' => 'nullable|string',
            'font_size' => 'nullable|integer',
            'invoice_prefix' => 'nullable|string',
        ]);

        $setting = InvoiceSetting::create($data);

        return response()->json($setting, 201);
    }

    /**
     * Display the specified resource.
     */

    public function show($restaurantId)
    {
        $setting = InvoiceSetting::where('restaurant_id', $restaurantId)->first();

        if (!$setting) {
            return ServiceResponse::error('Invoice setting not found for this restaurant');
        }

        return ServiceResponse::success('Invoice setting fetched successfully', [
            'invoice_setting' => new InvoiceResource($setting)
        ]);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceSetting $request, $id)
    {
        $restaurantId = $request->restaurant_id;

        $setting = InvoiceSetting::where('id', $id)
            ->where('restaurant_id', $restaurantId)
            ->first();

        if (!$setting) {
            return ServiceResponse::error('Invoice setting not found for this restaurant');
        }

        // Only take keys that were actually sent (not nulls/defaults)
        $data = array_filter(
            $request->only(array_keys($request->rules())),
            fn($value) => !is_null($value) && $value !== ''
        );

        // Special handling for images
        foreach (['invoice_logo', 'google_review_barcode'] as $field) {
            if (array_key_exists($field, $data) && !empty($data[$field])) {
                if ($setting->{$field}) {
                    Helper::deleteImage($setting->{$field});
                }
                $data[$field] = Helper::getBase64ImageUrl($data[$field], 'invoice');
            } else {
                // If key was not in request, remove it so it doesn't overwrite
                unset($data[$field]);
            }
        }

        $setting->update($data);

        return ServiceResponse::success('Invoice setting updated successfully', [
            'invoice_setting' => new InvoiceResource($setting)
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $setting = InvoiceSetting::find($id);

        if (!$setting) {
            return ServiceResponse::error('Invoice setting not found');
        }

        foreach (['invoice_logo', 'google_review_barcode'] as $field) {
            if ($setting->{$field}) {
                Helper::deleteImage($setting->{$field});
            }
        }

        $setting->delete();

        return ServiceResponse::success('Invoice setting deleted successfully');
    }

    /**
     * Bulk delete invoice settings.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|exists:invoice_settings,id',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $ids = $request->input('ids', []);

        $settings = InvoiceSetting::whereIn('id', $ids)->get();

        foreach ($settings as $setting) {
            foreach (['invoice_logo', 'google_review_barcode'] as $field) {
                if ($setting->{$field}) {
                    Helper::deleteImage($setting->{$field});
                }
            }
            $setting->delete();
        }

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }
}
