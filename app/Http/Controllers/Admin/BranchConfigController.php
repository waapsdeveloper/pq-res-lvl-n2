<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BranchConfig;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Helpers\ServiceResponse;

class BranchConfigController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);

        $query = BranchConfig::with(['branch', 'branch.timings', 'branch.orders']);

        // Search by restaurant name
        if ($search) {
            $query->whereHas('branch', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Apply filters
        if ($filters) {
            $filters = is_array($filters) ? $filters : json_decode($filters, true);

            if (!empty($filters['restaurant_name'])) {
                $query->whereHas('branch', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['restaurant_name'] . '%');
                });
            }
            if (!empty($filters['currency'])) {
                $query->where('currency', $filters['currency']);
            }
            if (!empty($filters['dial_code'])) {
                $query->whereHas('branch', function ($q) use ($filters) {
                    $q->where('dial_code', $filters['dial_code']);
                });
            }
            if (!empty($filters['tax'])) {
                $query->where('tax', $filters['tax']);
            }
        }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Transform each item to your required structure
        $data->getCollection()->transform(function ($config) {
            $branch = $config->branch;
            $currency = Currency::where('currency_code', $config->currency)->first();

            return [
                'id' => $config->id,
                'name' => $branch->name,
                'identifier' => $branch->identifier,
                'no_of_seats' => $branch->no_of_seats,
                'description' => $branch->description,
                'floor' => $branch->floor,
                'status' => $branch->status,
                'restaurant_id' => $branch->restaurant_id,
                'total_orders' => $branch->orders ? $branch->orders->count() : 0,
                'qr_code' => url('/tabs/products?table_identifier=' . $branch->identifier),
                'restaurant_detail' => [
                    'name' => $branch->name ?? '',
                    'address' => $branch->address ?? '',
                    'phone' => $branch->phone ?? '',
                    'email' => $branch->email ?? '',
                    'website' => $branch->website ?? '',
                    'rating' => $branch->rating ?? '',
                    'status' => $branch->status ?? '',
                    'dial_code' => $branch->dial_code ?? '',
                    'currency_symbol' => $branch->currency_symbol ?? '',
                    'tax' => $branch->tax ?? '',
                ],
                'currency' => $config->currency,
                'tax' => $config->tax,
                'dial_code' => $currency ? $currency->dial_code : null,
            ];
        });

        return ServiceResponse::success('Branch config list successfully retrieved', ['data' => $data]);
    }

    public function create()
    {
        $currencies = Currency::all();
        return ServiceResponse::success('Currency list successfully retrieved', ['data' => $currencies]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'branch_id' => 'required|exists:restaurants,id',
            'tax' => 'nullable|numeric|min:0|max:50',
            'currency' => 'required|string|max:3',
            'dial_code' => 'required|string|max:10',
        ]);

        // Check if config already exists for this branch
        $existingConfig = BranchConfig::where('branch_id', $data['branch_id'])->first();
        if ($existingConfig) {
            return ServiceResponse::error('Branch configuration already exists. Please update the existing configuration.', ['data' => null], 409);
        }

        // Create a new branch configuration
        $config = BranchConfig::create($data);

        // Update the restaurant with the tax, currency, and dial code
        $restaurant = \App\Models\Restaurant::find($data['branch_id']);
        if ($restaurant) {
            $restaurant->tax = $data['tax'] ?? 0; // Default tax to 0 if not provided
            $restaurant->currency = $data['currency'];
            $restaurant->dial_code = $data['dial_code'];
            $restaurant->save();
        }

        return ServiceResponse::success('Branch configuration created successfully', ['data' => $config]);
    }

    public function show($id)
    {
        $config = BranchConfig::with('branch')->findOrFail($id);

        // Access the restaurant (branch) details through the relationship
        $restaurant = $config->branch;

        // You can now include the restaurant details in your response
        $data = [
            'branch_config' => $config,
            'restaurant' => $restaurant,
        ];

        return ServiceResponse::success('Branch configuration retrieved successfully', ['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'branch_id' => 'required|exists:restaurants,id',
            'tax' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|max:3',
            'dial_code' => 'nullable|string|max:10',
        ]);

        // Find the configuration by ID
        $config = BranchConfig::find($id);

        if ($config) {
            // Update the existing configuration
            $config->update($data);
            $message = 'Branch configuration updated successfully';
        } else {
            // Create a new configuration if not found
            $config = BranchConfig::create($data);

            // Update the restaurant with the tax, currency, and dial code
            $restaurant = \App\Models\Restaurant::find($data['branch_id']);
            if ($restaurant) {
                $restaurant->tax = $data['tax'] ?? 0; // Default tax to 0 if not provided
                $restaurant->currency = $data['currency'];
                $restaurant->dial_code = $data['dial_code'];
                $restaurant->save();
            }

            $message = 'Branch configuration created successfully';
        }

        return ServiceResponse::success($message, ['data' => $config]);
    }

    public function destroy($id)
    {
        $config = BranchConfig::findOrFail($id);
        $config->delete();

        return ServiceResponse::success('Branch configuration deleted successfully', ['data' => null]);
    }
}
