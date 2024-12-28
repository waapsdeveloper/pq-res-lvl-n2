<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use App\Http\Resources\Admin\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAddresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        $query = User::query()->with('role')->with('userDetail')->orderBy('id', 'desc');
        // Optionally apply search filter if needed
        $query->where('role_id', '!=', 1);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($filters) {
            $filters = json_decode($filters, true);

            if (isset($filters['name']) && !empty($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (isset($filters['phone']) && !empty($filters['phone'])) {
                $query->where('phone', 'like', '%' . $filters['phone'] . '%');
            }
            if (isset($filters['email']) && !empty($filters['email'])) {
                $query->where('email', 'like', '%' . $filters['email'] . '%');
            }

            if (isset($filters['status']) && !empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['role']) && !empty($filters['role'])) {
                $query->whereHas('role', function ($query) use ($filters) {
                    $query->where('id', $filters['role']); // Assuming 'name' is a column in the roles table
                });
            }
        }


        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new UserResource($item);
        });
        // Return the response with image URLs included
        return ServiceResponse::success("Users retrieved successfully", ['data' => $data]);
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

        // Wrap the operations in a database transaction

        // Create a new user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'status' => $data['status'],
            'restaurant_id' => $data['restaurant_id'],
        ]);


        $userDetail = $user->userDetail()->create([
            'user_id' => $user->id,
            'address' => $data['address'],
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'country' => $data['country'] ?? null,
        ]);

        if (!$userDetail) {
            throw new \Exception('Failed to create user details.');
        }

        if (isset($data['image'])) {
            $url = Helper::getBase64ImageUrl($data['image'], 'user'); // Assuming a helper to handle the image upload
            $user->update(['image' => $url]);
        }
        DB::commit();

        return ServiceResponse::success('User store successful', ['user' => $user]);
    }



    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        //
        // Attempt to find the restaurant by ID
        $user = User::with('role', 'userDetail')->find($id);
        $user['role_id'] = $user->role_id;
        $user['role'] = $user->role->name;
        $user['address'] = $user->userDetail->address;
        $user['city'] = $user->userDetail->city;
        $user['state'] = $user->userDetail->address;
        $user['country'] = $user->userDetail->address;



        // If the restaurant doesn't exist, return an error response
        if (!$user) {
            return ServiceResponse::error("User not found", 404);
        }

        // Return a success response with the restaurant data
        return ServiceResponse::success("User details retrieved successfully", ['user' => $user]);
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

        // Wrap the operations in a database transaction
        // Find the user
        $user = User::find($id);
        if (!$user) {
            return ServiceResponse::error("User with ID $id not found.");
        }
        if (isset($data['image'])) {
            if ($user->image) {
                Helper::deleteImage($user->image);
            }
            $url = Helper::getBase64ImageUrl($data['image'], 'user');
            $data['image'] = $url;
        }
        // Update user details
        $user->update([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'phone' => $data['phone'] ?? $user->phone,
            'role_id' => $data['role_id'] ?? $user->role_id,
            'status' => $data['status'] ?? $user->status,
            "restaurant_id" => $data['restaurant_id'] ?? $user->restaurant_id,
            'image' => $data['image'] ?? $user->image,
        ]);

        // Optionally update the password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $user->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        // Update user detail record
        $userDetail = $user->userDetail;
        if ($userDetail) {
            $userDetail->update([
                'address' => $data['address'] ?? $userDetail->address,
                'city' => $data['city'] ?? $userDetail->city,
                'state' => $data['state'] ?? $userDetail->state,
                'country' => $data['country'] ?? $userDetail->country,
            ]);
        } else {
            // If user detail doesn't exist, create it
            $userDetail = $user->userDetail()->create([
                'user_id' => $user->id,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'country' => $data['country'] ?? null,
            ]);
        }

        if (!$userDetail) {
            throw new \Exception('Failed to update or create user details.');
        }

        // Commit the transaction
        DB::commit();

        return ServiceResponse::success('User updated successfully', ['user' => $user]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Attempt to find the restaurant by ID
        $user = User::find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$user) {
            return ServiceResponse::error("User not found", 404);
        }

        // Delete the restaurant
        $user->delete();

        // Return a success response
        return ServiceResponse::success("User deleted successfully.");
    }

    public function getAuthUser(Request $request)
    {

        $auth = Auth::user();
        $user = User::where('email', $auth->email)->first();

        if (!$user) {
            return ServiceResponse::error("User not found", 404);
        }

        return ServiceResponse::success("User fetch successfully.", ['user' => $user]);
    }
}
