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
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);

        $active_restaurant = Helper::getActiveRestaurantId();
        $resID = $request->restaurant_id == -1 ? $active_restaurant->id : $request->restaurant_id;

        // Get the customer role id
        $customerRole = Role::where('slug', 'customer')->first();
        $customerRoleId = $customerRole ? $customerRole->id : null;

        // Start the query and exclude Super Admin and Customer from the results
        $query = User::query()
            ->where('restaurant_id', $resID)
            ->with('role', 'userDetail')
            ->orderBy('id', 'desc')
            ->where('role_id', '!=', 1); // Exclude Super Admin

        if ($customerRoleId) {
            $query->where('role_id', '!=', $customerRoleId); // Exclude Customer
        }

        // Apply search if provided
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Apply filters if provided
        if ($filters) {
            $filters = json_decode($filters, true); // Decode JSON to array

            // Apply name filter
            if (isset($filters['name']) && !empty($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            // Apply phone filter
            if (isset($filters['phone']) && !empty($filters['phone'])) {
                $query->where('phone', 'like', '%' . $filters['phone'] . '%');
            }

            // Apply email filter
            if (isset($filters['email']) && !empty($filters['email'])) {
                $query->where('email', 'like', '%' . $filters['email'] . '%');
            }

            // Apply status filter
            if (isset($filters['status']) && !empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            // Apply role filter (if role is provided and is not Super Admin)
            if (isset($filters['role_id']) && !empty($filters['role_id'])) {
                $query->where('role_id', $filters['role_id']);
            }
        }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and transform them using UserResource
        $data->getCollection()->transform(function ($item) {
            $item->total_orders = $item->orders()->count(); // Count total orders for the user
            return new UserResource($item);
        });

        // Return the response with the data
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



        // Extract phone number and country code if phone is an array/object
        $phone = is_array($data['phone']) || is_object($data['phone'])
            ? (is_array($data['phone']) ? ($data['phone']['number'] ?? null) : ($data['phone']->number ?? null))
            : $data['phone'];
        $dial_code = is_array($data['phone']) || is_object($data['phone'])
            ? (is_array($data['phone']) ? ($data['phone']['countryCode'] ?? null) : ($data['phone']->countryCode ?? null))
            : null;

        // Create a new user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $phone,
            'dial_code' => $dial_code,
            'password' => Hash::make($data['password']),

            'role_id' => $data['role_id'] ?? 0,
            'status' => $data['status'],
            'restaurant_id' => $data['restaurant_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        if (isset($data['image'])) {
            $url = Helper::getBase64ImageUrl($data['image'], 'user');
            if (is_array($url) && isset($url['status']) && $url['status'] === 'error') {
                return $url; // Return the ServiceResponse::error directly
            }
            $user->update(['image' => $url]);
        }
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
        // Attempt to find the user with relationships
        $user = User::with(['role', 'userDetail'])->find($id);

        // If the user doesn't exist, return an error response
        if (!$user) {
            return ServiceResponse::error("User not found", 404);
        }

        // Transform user data using resource
        $data = new UserResource($user);

        // Return a success response with the user data
        return ServiceResponse::success("User details retrieved successfully", ['user' => $data]);
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

        $user = User::find($id);
        if (!$user) {
            return ServiceResponse::error("User with ID $id not found.");
        }

        // Extract phone number and country code if phone is an array/object
        $phone = is_array($data['phone']) || is_object($data['phone'])
            ? (is_array($data['phone']) ? ($data['phone']['number'] ?? null) : ($data['phone']->number ?? null))
            : $data['phone'];
        $dial_code = is_array($data['phone']) || is_object($data['phone'])
            ? (is_array($data['phone']) ? ($data['phone']['countryCode'] ?? null) : ($data['phone']->countryCode ?? null))
            : null;

        if (isset($data['image'])) {
            if ($user->image) {
                Helper::deleteImage($user->image);
            }
            $url = Helper::getBase64ImageUrl($data['image'], 'user');
            $data['image'] = $url;
        }

        $user->update([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'phone' => $phone ?? $user->phone,
            'dial_code' => $dial_code ?? $user->dial_code,
            'role_id' => $data['role_id'] ?? $user->role_id,
            'status' => $data['status'] ?? $user->status,
            "restaurant_id" => $data['restaurant_id'] ?? $user->restaurant_id,
            'image' => $data['image'] ?? $user->image,
            'updated_at' => now(),
        ]);

        if (isset($data['password']) && !empty($data['password'])) {
            $user->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        $userDetail = $user->userDetail;
        if ($userDetail) {
            $userDetail->update([
                'address' => $data['address'] ?? $userDetail->address,
                'city' => $data['city'] ?? $userDetail->city,
                'state' => $data['state'] ?? $userDetail->state,
                'country' => $data['country'] ?? $userDetail->country,
            ]);
        } else {
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

        UserAddresses::where('user_id', $id)->delete();
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

    public function getAuthUserPermissions()
    {
        $auth = Auth::user();
        $user = User::where('email', $auth->email)->first();

        if (!$user) {
            return ServiceResponse::error("User not found", 404);
        }

        // Get the role of the user
        $role = Role::find($user->role_id);

        if (!$role) {
            return ServiceResponse::error("Role not found for the user", 404);
        }

        // Get permissions associated with the role
        $permissions = $role->permissions;

        return ServiceResponse::success("User permissions fetched successfully.", ['permissions' => $permissions]);
    }
}
