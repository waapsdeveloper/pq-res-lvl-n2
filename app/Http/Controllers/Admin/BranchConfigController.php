<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BranchConfig;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Helpers\ServiceResponse;
use App\Models\Restaurant;
use Illuminate\Validation\Rule;

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
        $data->transform(function ($config) {
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
            'country' => 'nullable|string|max:255',
            'tax' => 'nullable|numeric|min:0|max:50',
            'currency' => 'required|string|max:3',
            'dial_code' => 'required|string|max:10',
            'currency_symbol' => 'nullable|string|max:10',
            'delivery_charges' => 'nullable|numeric|min:0|max:1000',
            'tips' => 'nullable|numeric|min:0|max:50',
            'enableTax' => 'nullable|boolean',
            'enableDeliveryCharges' => 'nullable|boolean',
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
            $restaurant->country = $data['country'] ?? null;
            $restaurant->enableTax = $data['enableTax'] ?? true;
            $restaurant->enableDeliveryCharges = $data['enableDeliveryCharges'] ?? true;
            $restaurant->save();
        }

        return ServiceResponse::success('Branch configuration created successfully', ['data' => $config]);
    }

    public function show($id)
    {
        
        $config = BranchConfig::with('branch')->where('branch_id', $id)->first();
        
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
            'country' => 'nullable|string|max:255',
            'tax' => 'nullable|numeric|min:0|max:50',
            'currency' => 'required|string|max:3',
            'dial_code' => 'required|string|max:10',
            'currency_symbol' => 'nullable|string|max:10',
            'tips' => 'nullable|numeric|min:0|max:50',
            'delivery_charges' => 'nullable|numeric|min:0|max:1000',
            'enableTax' => 'nullable|boolean',
            'enableDeliveryCharges' => 'nullable|boolean',
        ]);

        $config = BranchConfig::find($id);

        if (!$config) {
            return ServiceResponse::error('Branch configuration not found', ['data' => null], 404);
        }

        // Update the existing configuration
        $config->update([
            'country' => $data['country'] ?? null,
            'tax' => $data['tax'] ?? 0,
            'currency' => $data['currency'],
            'dial_code' => $data['dial_code'],
            'tips' => $data['tips'] ?? 0,
            'delivery_charges' => $data['delivery_charges'] ?? 0,
            'enableTax' => $data['enableTax'] ?? true,
            'enableDeliveryCharges' => $data['enableDeliveryCharges'] ?? true,
            'currency_symbol' => Currency::where('currency_code', $data['currency'])->value('currency_symbol'),
        ]);

        // Update the restaurant with the tax, currency, and dial code
        $restaurant = \App\Models\Restaurant::find($data['branch_id']);
        if ($restaurant) {
            $restaurant->tax = $data['tax'] ?? 0;
            $restaurant->currency = $data['currency'];
            $restaurant->dial_code = $data['dial_code'];
            $restaurant->country = $data['country'] ?? null;
            $restaurant->tips = $data['tips'] ?? 0;
            $restaurant->delivery_charges = $data['delivery_charges'] ?? 0;
            $restaurant->enableTax = $data['enableTax'] ?? true;
            $restaurant->enableDeliveryCharges = $data['enableDeliveryCharges'] ?? true;
            $restaurant->save();
        }

        $message = 'Branch configuration updated successfully';

        // Fetch the related restaurant (branch) details
        $config->load('branch');
        $restaurant = $config->branch;

        // update currency symbol according to currency code
        $currency = Currency::where('currency_code', $config->currency)->first();
        if ($currency) {
            $config->currency_symbol = $currency->currency_symbol;
            $config->save();
        }

        $responseData = [
            'branch_config' => $config,
            'restaurant' => $restaurant,
        ];

        return ServiceResponse::success($message, ['data' => $responseData]);
    }

    public function destroy($id)
    {
        $config = BranchConfig::findOrFail($id);
        $config->delete();

        return ServiceResponse::success('Branch configuration deleted successfully', ['data' => null]);
    }

    public function getRestaurantConfigById(Request $request, $id)
    {
        $restaurant = Restaurant::where('id', $id)->first();
        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found', [], 404);
        }

        $config = BranchConfig::where('branch_id', $id)->first();
        if (!$config) {
            // If no config exists, create a default one
            $config = BranchConfig::create([
                'branch_id' => $id,
                'country' => null,
                'currency' => 'USD', // Default currency, can be changed later
                'tax' => 0, // Default tax, can be changed later
                'dial_code' => '+1', // Default dial code, can be changed later
                'currency_symbol' => '$', // Default currency symbol, can be changed later
                'tips' => 0,
                'delivery_charges' => 0, // Default delivery charges
                'enableTax' => true, // Default enable tax
                'enableDeliveryCharges' => true, // Default enable delivery charges
            ]);
        }

        return ServiceResponse::success('Restaurant config retrieved successfully', ['data' => $config]);
    }

    public function updateOrderSettings(Request $request)
    {
        $data = $request->validate([
            'branch_id' => 'required|exists:restaurants,id',
            'name' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'tax' => 'nullable|numeric|min:0|max:50',
            'currency' => 'required|string|max:3',
            'enableDeliveryCharges' => 'nullable|boolean',
            'enableTax' => 'nullable|boolean',
            'dial_code' => 'required|string|max:10',
            'delivery_charges' => 'nullable|numeric|min:0|max:1000',
        ]);

        // Find or create branch config
        $config = BranchConfig::where('branch_id', $data['branch_id'])->first();

        if (!$config) {
            // Create new config if it doesn't exist
            $config = new BranchConfig();
            $config->branch_id = $data['branch_id'];
        }

        // Update config with order settings
        $config->currency = $data['currency'];
        $config->dial_code = $data['dial_code'];
        $config->tax = $data['enableTax'] ? ($data['tax'] ?? 0) : 0;
        $config->delivery_charges = $data['enableDeliveryCharges'] ? ($data['delivery_charges'] ?? 0) : 0;
        $config->currency_symbol = Currency::where('currency_code', $data['currency'])->value('currency_symbol');
        $config->save();

        // Update restaurant with order settings
        $restaurant = Restaurant::find($data['branch_id']);
        if ($restaurant) {
            $restaurant->name = $data['name'] ?? $restaurant->name;
            $restaurant->country = $data['country'] ?? $restaurant->country;
            $restaurant->currency = $data['currency'];
            $restaurant->dial_code = $data['dial_code'];
            $restaurant->enableTax = $data['enableTax'];
            $restaurant->enableDeliveryCharges = $data['enableDeliveryCharges'];
            $restaurant->tax = $data['enableTax'] ? ($data['tax'] ?? 0) : 0;
            $restaurant->delivery_charges = $data['enableDeliveryCharges'] ? ($data['delivery_charges'] ?? 0) : 0;
            $restaurant->save();
        }

        return ServiceResponse::success('Order settings updated successfully', ['data' => $config]);
    }
}
