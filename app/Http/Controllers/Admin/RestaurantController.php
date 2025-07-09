<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\DateHelper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Restaurant\UpdateRestaurant;
use App\Http\Requests\Admin\Restaurant\StoreRestaurant;
use App\Http\Requests\Admin\Restaurant\UpdateActiveRestaurant;
use App\Http\Resources\Admin\RestaurantListResourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\RestaurantTiming;
use App\Models\Restaurant;
use App\Models\RestaurantTimings;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Admin\RestautrantSetting\StoreRestaurantSetting;
use App\Models\BranchConfig;
use App\Models\RestaurantSetting;
use App\Models\RestaurantMeta;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PHPUnit\TextUI\Help;


class RestaurantController extends Controller
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

        // Order by is_active DESC first, then by id DESC
        $query = Restaurant::query()->with('timings', 'settings', 'meta')->orderByDesc('is_active')->orderByDesc('id');
        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($filters) {
            $filters = json_decode($filters, true); // Decode JSON string into an associative array

            if (isset($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (isset($filters['address'])) {
                $query->where('address', 'like', '%' . $filters['address'] . '%');
            }

            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }
        }
        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        $data->getCollection()->transform(function ($item) {
            return new RestaurantListResourse($item);
        });

        // Return the response with image URLs included
        return ServiceResponse::success("Trial list successfully", ['data' => $data]);
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
    public function store(StoreRestaurant $request)
    {
        if ($request->has('logo')) {
            $image = $request->input('logo');
            $fileSize = strlen($image) * 3 / 4; // Approximate size in bytes
            if ($fileSize > 3 * 1024 * 1024) {
                return response()
                    ->json(ServiceResponse::error('Image size exceeds 3 MB.'))
                    ->setStatusCode(422);
            }
        }
        $data = $request->validated();

        // Create the restaurant
        $restaurant = Restaurant::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'active',
            'copyright_text' => $data['copyright_text'] ?? null,
            'rating' => $data['rating'] ?? 0,
        ]);

        // Process image, favicon, and logo (Base64 conversion)
        foreach (['image', 'favicon', 'logo'] as $field) {
            if (isset($data[$field]) && $data[$field]) {
                $url = Helper::getBase64ImageUrl($data[$field], 'restaurant');
                $restaurant->update([$field => $url]);
            }
        }

        // Handle timing configuration
        if (isset($data['timings']) && is_array($data['timings'])) {
            $timingConfig = [];

            foreach ($data['timings'] as $timing) {
                if (isset($timing['key']) && isset($timing['value'])) {
                    $timingConfig[$timing['key']] = $timing['value'];
                }
            }

            if (!empty($timingConfig)) {
                RestaurantTiming::setTimingConfig($restaurant->id, $timingConfig);
            }
        }

        // create branch config as well
        $branchConfig = BranchConfig::firstOrCreate([
            'branch_id' => $restaurant->id,
            'tax' => $data['tax'] ?? 0,
            'currency' => $data['currency'] ?? 'USD',
            'dial_code' => $data['dial_code'] ?? '+1',
            'delivery_charges' => $data['delivery_charges'] ?? 0,
            'country' => $data['country'] ?? 'United States',
            // 'tips' => $data['tips'] ?? 0,

        ]);

        // Store meta data if provided
        if (isset($data['meta']) && is_array($data['meta'])) {
            Log::info('Meta data received for restaurant store:', ['meta' => $data['meta']]);
            foreach ($data['meta'] as $metaItem) {
                Log::info('Processing meta item:', ['meta_item' => $metaItem]);
                if (isset($metaItem['key'])) {
                    RestaurantMeta::create([
                        'restaurant_id' => $restaurant->id,
                        'meta_key' => $metaItem['key'],
                        'meta_value' => $metaItem['value'] ?? null,
                    ]);
                    Log::info('Meta item stored:', [
                        'restaurant_id' => $restaurant->id,
                        'meta_key' => $metaItem['key'],
                        'meta_value' => $metaItem['value'] ?? null,
                    ]);
                } else {
                    Log::warning('Meta item missing key:', ['meta_item' => $metaItem]);
                }
            }
        } else {
            Log::info('No meta data provided for restaurant store.');
        }

        return ServiceResponse::success('Store successful', ['restaurant' => $restaurant]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        // Attempt to find the restaurant by ID
        $restaurant = Restaurant::with('timings', 'settings', 'meta')->find($id);
        // $restaurant['image'] = Helper::returnFullImageUrl($restaurant->image);
        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return ServiceResponse::error("Restaurant not found", 404);
        }
        $data = new RestaurantListResourse($restaurant);
        // Return a success response with the restaurant data
        return ServiceResponse::success("Restaurant details retrieved successfully", ['restaurant' => $data]);
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
    public function update(UpdateRestaurant $request, string $id)
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
        
        // Debug: Log the entire request data
        logger()->info('Restaurant update request data:', $request->all());
        logger()->info('Validated data:', $data);

        $restaurant = Restaurant::find($id);


        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }
        // return response()->json([$data]);
        // Delete old images
        foreach (['image', 'favicon', 'logo'] as $field) {
            if (isset($data[$field]) && $data[$field] && $restaurant->{$field}) {
                Helper::deleteImage($restaurant->{$field});
            }
        }

        // Update restaurant details (using fill and save for cleaner code)
        $restaurant->fill([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'] ?? $restaurant->phone,
            'email' => $data['email'] ?? $restaurant->email,
            'website' => $data['website'] ?? $restaurant->website,
            'description' => $data['description'] ?? $restaurant->description,
            'status' => $data['status'] ?? $restaurant->status,
            'copyright_text' => $data['copyright_text'] ?? $restaurant->copyright_text,
            'rating' => $data['rating'] ?? $restaurant->rating,
        ]);

        $restaurant->save(); // Save the changes

        // Process new images
        foreach (['image', 'favicon', 'logo'] as $field) {
            if (isset($data[$field]) && $data[$field]) {
                $url = Helper::getBase64ImageUrl($data[$field], 'restaurant');
                $restaurant->update([$field => $url]); // Update only the image field
            }
        }

        // Handle timing configuration
        if (isset($data['timings']) && is_array($data['timings'])) {
            $timingConfig = [];

            foreach ($data['timings'] as $timing) {
                if (isset($timing['key']) && isset($timing['value'])) {
                    $timingConfig[$timing['key']] = $timing['value'];
                }
            }

            if (!empty($timingConfig)) {
                RestaurantTiming::setTimingConfig($restaurant->id, $timingConfig);
            }
        }

        // Update meta data if provided
        if (isset($data['meta']) && is_array($data['meta'])) {
            logger()->info('Meta data received:', $data['meta']);
            foreach ($data['meta'] as $metaItem) {
                if (isset($metaItem['key'])) {
                    logger()->info('Processing meta item:', $metaItem);
                    RestaurantMeta::updateOrCreate(
                        [
                            'restaurant_id' => $restaurant->id,
                            'meta_key' => $metaItem['key'],
                        ],
                        [
                            'meta_value' => $metaItem['value'] ?? '',
                        ]
                    );
                }
            }
        }

        return ServiceResponse::success('Update successful', ['restaurant' => $restaurant]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the restaurant by ID
        $restaurant = Restaurant::find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return ServiceResponse::error("Restaurant not found", 404);
        }

        // Delete timing data
        RestaurantTiming::where('restaurant_id', $restaurant->id)->delete();

        // Delete meta data
        RestaurantMeta::where('restaurant_id', $restaurant->id)->delete();

        // Delete the restaurant
        $restaurant->delete();

        // Return a success response
        return ServiceResponse::success("Restaurant deleted successfully.");
    }

    public function updateImage(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $validator = Validator::make($request->all(), [
            'image' => 'nullable|string|mimes:jpg,jpeg,png,gif,webp.bmp|max:2048',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $data = $request->all();

        if (isset($data['image'])) {
            $url = Helper::getBase64ImageUrl($data, 'restaurant');
            $restaurant->update([
                'image' => $url,
            ]);
        }

        return ServiceResponse::success('Update successful', ['restaurant' => $restaurant]);
    }

    public function updateLogo(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp.bmp|max:2048',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $data = $request->all();

        if (isset($data['logo'])) {
            $url = Helper::getBase64ImageUrl($data, 'restaurant');
            $restaurant->update([
                'logo' => $url,
            ]);
        }

        return ServiceResponse::success('Update successful', ['restaurant' => $restaurant]);
    }
    public function updateFavicon(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $validator = Validator::make($request->all(), [
            'favicon' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp.bmp|max:2048',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $data = $request->all();

        if (isset($data['favicon'])) {
            $url = Helper::getBase64ImageUrl($data, 'restaurant');
            $restaurant->update([
                'favicon' => $url,
            ]);
        }

        return ServiceResponse::success('Update successful', ['restaurant' => $restaurant]);
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|exists:restaurants,id',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }
        $ids = $request->input('ids', []);

        RestaurantTiming::whereIn('restaurant_id', $ids)->delete();

        // Delete meta data
        RestaurantMeta::whereIn('restaurant_id', $ids)->delete();

        Restaurant::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }
    public function setting(StoreRestaurantSetting $request)
    {
        $data = $request->validated();

        $setting = RestaurantSetting::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'restaurant_id' => $data['restaurant_id'],
                'meta_key'   => $data['meta_key'],
                'meta_value' => $data['meta_value'],
            ]
        );
        return ServiceResponse::success('Store successful', ['restaurant_setting' => $setting]);
    }
    public function showActiveRestaurant()
    {
        $activeRestaurantId = Helper::getActiveRestaurantId();
        return ServiceResponse::success('Active Restaurant ID', ['active_restaurant' => $activeRestaurantId]);
    }


    public function updateActiveRestaurant(UpdateActiveRestaurant $request, $id)
    {
        $data = $request->validated();

        // Ensure the restaurant with the given ID exists
        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        DB::transaction(function () use ($id, $data) {
            Restaurant::query()->update([
                'is_active' => DB::raw("CASE WHEN id = $id THEN {$data['is_active']} ELSE 0 END")
            ]);
        });

        $restaurant->refresh();

        return ServiceResponse::success('Restaurant activated successfully', [
            'restaurant' => $restaurant
        ]);
    }

    /**
     * Store or update restaurant meta data
     */
    public function storeMeta(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'meta_key' => 'required|string|max:255',
            'meta_value' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $meta = RestaurantMeta::updateOrCreate(
            [
                'restaurant_id' => $restaurant->id,
                'meta_key' => $request->meta_key,
            ],
            [
                'meta_value' => $request->meta_value,
            ]
        );

        return ServiceResponse::success('Meta data stored successfully', ['meta' => $meta]);
    }

    /**
     * Get restaurant meta data by key
     */
    public function getMeta(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $metaKey = $request->input('meta_key');

        if ($metaKey) {
            $meta = RestaurantMeta::where('restaurant_id', $restaurant->id)
                ->where('meta_key', $metaKey)
                ->first();

            return ServiceResponse::success('Meta data retrieved successfully', ['meta' => $meta]);
        }

        $meta = RestaurantMeta::where('restaurant_id', $restaurant->id)->get();

        return ServiceResponse::success('All meta data retrieved successfully', ['meta' => $meta]);
    }

    /**
     * Delete restaurant meta data
     */
    public function deleteMeta(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'meta_key' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return ServiceResponse::error('Restaurant not found');
        }

        $deleted = RestaurantMeta::where('restaurant_id', $restaurant->id)
            ->where('meta_key', $request->meta_key)
            ->delete();

        if ($deleted) {
            return ServiceResponse::success('Meta data deleted successfully');
        }

        return ServiceResponse::error('Meta data not found');
    }
}
