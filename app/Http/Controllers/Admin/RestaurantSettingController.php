<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RestautrantSetting\StoreRestaurantSetting;
use App\Models\RestaurantSetting;
use Illuminate\Http\Request;

class RestaurantSettingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);

        $query = ::query()->where('restaurant_id',$request->restaurant_id)->with('timings')->orderBy('id', 'desc');
        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // if ($filters) {
        //     $filters = json_decode($filters, true); // Decode JSON string into an associative array

        //     if (isset($filters['name'])) {
        //         $query->where('name', 'like', '%' . $filters['name'] . '%');
        //     }

        //     if (isset($filters['address'])) {
        //         $query->where('address', 'like', '%' . $filters['address'] . '%');
        //     }

        //     if (isset($filters['status'])) {
        //         $query->where('status', $filters['status']);
        //     }
        // }
        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new RestaurantListResourse($item);
        });

        // Return the response with image URLs included
        return ServiceResponse::success("Trial list successfully", ['data' => $data]);
    }
    public function restaurantSetting(StoreRestaurantSetting $request)
    {
        $data = $request->validated();
        // $data = $request->all();
        $setting = RestaurantSetting::updateOrCreate(
        ['id' => $validatedData['id'] ?? null], // If id exists, update that record
    [
                'restaurant_id' => $data['meta_key'],
                'meta_key'   => $data['meta_key'],
                'meta_value' => $data['meta_value'],
            ]
        );
        return ServiceResponse::success('Store successful', ['restaurant_setting' => $setting]);
    }
}
