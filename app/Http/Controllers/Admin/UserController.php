<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);

        $query = User::query();

        // Optionally apply search filter if needed
        $query->where('role_id', '!=', 1);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($filters) {
            $filters = json_decode($filters, true); // Decode JSON string into an associative array

            if (isset($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (isset($filters['phone'])) {
                $query->where('phone', 'like', '%' . $filters['phone'] . '%');
            }

            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }
        }



        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new UserResource($item);
        });

        // Return the response with image URLs included
        return self::success("Trial list successfully", ['data' => $data]);
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
    public function store(StoreUser $request)
    {
        $data = $request->validated();


        // Create a new user (assuming the user model exists)
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => bcrypt($data['password']),
            'role_id' => $data['role'],
            'status' => $data['status'],
        ]);

        // Optionally, handle the image if the data contains it
        if (isset($data['image'])) {
            $url = Helper::getBase64ImageUrl($data);  // Assuming a helper to handle the image upload
            $user->update([
                'image' => $url,
            ]);
        }

        return self::success('User store successful', ['user' => $user]);
    }


    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        //
        // Attempt to find the restaurant by ID
        $restaurant = User::find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return self::failure("User not found", 404);
        }

        // Return a success response with the restaurant data
        return self::success("User details retrieved successfully", ['user' => $restaurant]);
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
    public function update(UpdateUser $request, $id)
    {
        $data = $request->validated();

        // Find the user
        $user = User::find($id);
        if (!$user) {
            return self::failure("User with ID $id not found.");
        }

        // Update user details
        $user->update([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'phone' => $data['phone'] ?? $user->phone,
            'role_id' => $data['role'] ?? $user->role_id,
            'status' => $data['status'] ?? $user->status,
        ]);

        // Optionally update the password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $user->update([
                'password' => bcrypt($data['password']),
            ]);
        }

        // Optionally handle the image if provided
        if (isset($data['image'])) {
            $url = Helper::getBase64ImageUrl($data); // Assuming a helper to handle the image upload
            $user->update([
                'image' => $url,
            ]);
        }

        return self::success('User updated successfully', ['user' => $user]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the restaurant by ID
        $restaurant = User::find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$restaurant) {
            return self::failure("user not found", 404);
        }

        // Delete the restaurant
        $restaurant->delete();

        // Return a success response
        return self::success("User deleted successfully.");
    }

    public function getAuthUser(Request $request)
    {

        $auth = Auth::user();
        $user = User::where('email', $auth->email)->first();

        if (!$user) {
            return self::failure("user not found", 404);
        }

        return self::success("User fetch successfully.", ['user' => $user]);
    }
}
