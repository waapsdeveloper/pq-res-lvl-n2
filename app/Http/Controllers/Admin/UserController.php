<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use App\Http\Resources\Admin\UserResource;
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

        DB::beginTransaction();
        try {
            // Create a new user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'role_id' => $data['role_id'],
                'status' => $data['status'],
                'restaurant_id' => $data['restaurant_id'] ?? null,
            ]);

            // Create user addresses
            foreach ($data['userDetails'] as $detail) {
                if (!empty($detail) && isset($detail['address'], $detail['city'], $detail['state'])) {
                    UserAddresses::create([
                        'user_id' => $user->id,
                        'address' => ucfirst($detail['address']),
                        'city' => ucfirst($detail['city']),
                        'state' => ucfirst($detail['state']),
                        'country' => $detail['country'] ?? 'inactive',
                    ]);
                }
            }

            // Handle image upload
            if (isset($data['image'])) {
                $url = Helper::getBase64ImageUrl($data['image'], 'user');
                $user->update(['image' => $url]);
            }

            DB::commit();

            return ServiceResponse::success('User store successful', ['user' => $user]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error for debugging
            // Log::error('Failed to store user: ' . $e->getMessage());

            return ServiceResponse::error('Failed to store user');
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
        $user['image'] = Helper::returnFullImageUrl($user->image);

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

        DB::beginTransaction();

        try {
            // Find the user
            $user = User::find($id);

            if (!$user) {
                return ServiceResponse::error("User with ID $id not found.");
            }

            // Handle image update
            if (isset($data['image'])) {
                if ($user->image) {
                    Helper::deleteImage($user->image); // Delete the old image if it exists
                }
                $data['image'] = Helper::getBase64ImageUrl($data['image'], 'user'); // Upload the new image
            }

            // Update user details
            $user->update([
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'phone' => $data['phone'] ?? $user->phone,
                'role_id' => $data['role_id'] ?? $user->role_id,
                'status' => $data['status'] ?? $user->status,
                'restaurant_id' => $data['restaurant_id'] ?? $user->restaurant_id,
                'image' => $data['image'] ?? $user->image,
            ]);

            // Update password if provided
            if (isset($data['password']) && !empty($data['password'])) {
                $user->update([
                    'password' => Hash::make($data['password']),
                ]);
            }

            // Update user details (e.g., addresses)
            if (isset($data['userDetails']) && is_array($data['userDetails'])) {
                foreach ($data['userDetails'] as $detail) {
                    if (!empty($detail)) {
                        // Check if the address for this user already exists
                        $existingAddress = UserAddresses::where('user_id', $user->id)
                            ->where('address', ucfirst($detail['address'] ?? ''))
                            ->first();

                        if ($existingAddress) {
                            // Update existing address if any fields are provided
                            $existingAddress->update([
                                'city' => ucfirst($detail['city'] ?? $existingAddress->city),
                                'state' => ucfirst($detail['state'] ?? $existingAddress->state),
                                'country' => ucfirst($detail['country'] ?? $existingAddress->country),
                            ]);
                        } else {
                            // Create a new address if no match is found
                            UserAddresses::create([
                                'user_id' => $user->id,
                                'address' => ucfirst($detail['address'] ?? 'N/A'),
                                'city' => ucfirst($detail['city'] ?? 'N/A'),
                                'state' => ucfirst($detail['state'] ?? 'N/A'),
                                'country' => ucfirst($detail['country'] ?? 'Inactive'),
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return ServiceResponse::success('User updated successfully', ['user' => $user]);
        } catch (\Exception $e) {
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
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|exists:restaurant_timings,id',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $ids = $request->input('ids', []);
        User::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }
}
