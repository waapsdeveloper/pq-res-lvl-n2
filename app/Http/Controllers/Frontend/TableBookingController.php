<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TableBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
        //
        $data = $request->all();
        // $data = $request->validated();

        // Validate the required fields
        $validation = Validator::make($data, [
            'no_of_guests' => 'required|number|min:2|max:10',
            'date' => 'required|integer|exists:categories,id', // Ensure role is provided
            'time' => 'required|string|in:active,inactive', // Validate status
        ]);

        // // If validation fails
        // if ($validation->fails()) {
        //     return self::failure($validation->errors()->first());
        // }

        // Create a new user (assuming the user model exists)
        $user = Category::create([
            'name' => $data['name'],
            'category_id' => $data['category'] ?? 0,
            'status' => $data['status'],
        ]);

        return ServiceResponse::success('Category store successful', ['Category' => $user]);
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

    public function checkTableAvailability(Request $request){

        $data = $request->all();
        // $data = $request->validated();

        // Validate the required fields
        $validation = Validator::make($data, [
            'no_of_guests' => 'required|integer|min:2|max:10',
            'date' => 'required|date', // Ensure role is provided
            'time' => 'required|time', // Validate status
        ]);

        if ($validation->fails()) {
            return self::failure($validation->errors()->first());
        }


        return ServiceResponse::success('table availability', ['data' => $data]);


    }
}
