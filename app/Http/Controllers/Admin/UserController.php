<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
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

        $query = User::query()->with('role');
        // Optionally apply search filter if needed
        $query->where('role_id', '!=', 1);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        $filters = json_decode($filters, true);
        if ($filters) {
            if (!empty($filters['role'])) {
                dd($filters['role']);
                $query->where('role_id', $filters['role']);
            }
            if (!empty($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (!empty($filters['phone'])) {
                $query->where('phone', 'like', '%' . $filters['phone'] . '%');
            }

            if (!empty($filters['email'])) {
                $query->where('email', 'like', '%' . $filters['email'] . '%');
            }

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
        }



        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new UserResource($item);
        });

        // dd($data);
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

    public function store(StoreUser $request)
    {
        $data = $request->validated();

        // Wrap the operations in a database transaction
        DB::beginTransaction();
        try {
            // Create a new user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'role_id' => $data['role'],
                'status' => $data['status'],
                'restaurant_id' => $data['restaurant_id'] ?? null,
            ]);


            $userDetail = $user->userDetail()->create([
                'user_id' => $user->id,
                'address_line' => $data['address'],
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'country' => $data['country'] ?? null,
            ]);

            if (!$userDetail) {
                // Rollback if user details are not created
                throw new \Exception('Failed to create user details.');
            }

            // Optionally handle the image
            if (isset($data['image'])) {
                $url = Helper::getBase64ImageUrl($data); // Assuming a helper to handle the image upload
                $user->update(['image' => $url]);
            }

            // Commit the transaction
            DB::commit();

            return ServiceResponse::success('User store successful', ['user' => $user]);
        } catch (\Exception $e) {
            // Rollback the transaction on any failure
            DB::rollBack();

            return ServiceResponse::error('Failed to store user: ' . $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        //
        // Attempt to find the restaurant by ID
        $user = User::with('role')->find($id);
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
        DB::beginTransaction();

        try {
            // Find the user
            $user = User::find($id);
            if (!$user) {
                return ServiceResponse::error("User with ID $id not found.");
            }
            // Update user details
            $user->update([
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'phone' => $data['phone'] ?? $user->phone,
                'role_id' => $data['role'] ?? $user->role_id,
                'status' => $data['status'] ?? $user->status,
                "restaurant_id" => $data['restaurant_id'] ?? $user->restaurant_id,
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
                    'address_line' => $data['address'] ?? $userDetail->address_line,
                    'city' => $data['city'] ?? $userDetail->city,
                    'state' => $data['state'] ?? $userDetail->state,
                    'country' => $data['country'] ?? $userDetail->country,
                ]);
            } else {
                // If user detail doesn't exist, create it
                $userDetail = $user->userDetail()->create([
                    'user_id' => $user->id,
                    'address_line' => $data['address'] ?? null,
                    'city' => $data['city'] ?? null,
                    'state' => $data['state'] ?? null,
                    'country' => $data['country'] ?? null,
                ]);
            }

            if (!$userDetail) {
                throw new \Exception('Failed to update or create user details.');
            }

            // Optionally handle the image if provided
            if (isset($data['image'])) {
                $url = Helper::getBase64ImageUrl($data); // Assuming a helper to handle the image upload
                $user->update([
                    'image' => $url,
                ]);
            }

            // Commit the transaction
            DB::commit();

            return ServiceResponse::success('User updated successfully', ['user' => $user]);
        } catch (\Exception $e) {
            // Rollback the transaction on any failure
            DB::rollBack();

            return ServiceResponse::error('Failed to update user: ' . $e->getMessage());
        }
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
            return ServiceResponse::error("user not found", 404);
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
            return ServiceResponse::error("user not found", 404);
        }

        return ServiceResponse::success("User fetch successfully.", ['user' => $user]);
    }
}
