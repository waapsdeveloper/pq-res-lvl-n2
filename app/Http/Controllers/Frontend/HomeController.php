<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\Role;


class HomeController extends Controller
{
    public function roles()
    {
        $roles = Role::get();
        return ServiceResponse::success('roles are retrived successfully', ['data' => $roles]);
    }

    public function restautantDetail($id)
    {
        $restuarant = Restaurant::with('timings', 'rTables')->findOrFail($id);
        return ServiceResponse::success('Restaurant are retrived successfully', ['data' => $restuarant]);
    }
    public function showActiveRestaurant()
    {
        $activeRestaurant = Helper::getActiveRestaurantId();
        if ($activeRestaurant) {
            return ServiceResponse::success('Active Restaurant ID', ['active_restaurant' => $activeRestaurant]);
        } else {
            return ServiceResponse::error('No active restaurant found.');
        }
    }
}
