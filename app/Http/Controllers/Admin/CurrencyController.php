<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Helpers\ServiceResponse;


class CurrencyController extends Controller
{
    public function index()
    {
        $data = Currency::all();
        return ServiceResponse::success("Currency list successfully retrieved", ['data' => $data]);
    }
}