<?php

namespace App\Http\Controllers;

use App\Helpers\ServiceResponse;
use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    //
    /**
     * Fetch all countries.
     */
    public function index()
    {
        $countries = Country::all();
        return ServiceResponse::success('User addresses added successfully', ['countries' => $countries]);
    }
}
