<?php

namespace App\Http\Controllers;

use App\Helpers\ServiceResponse;
use App\Models\Profiles;
use App\Models\User;
use App\Models\UserAddresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPUnit\Metadata\Uses;

class ProfileController extends Controller
{
    //

    public function updateUser(Request $request)
    {

        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string',
            'dial_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error($validator->errors()->first());
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'dial_code' => $request->dial_code,            
        ]);

        $user = User::where('id', '=', $user->id)->first();

        return ServiceResponse::success('profile update successful', ['user' => $user]);

    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error($validator->errors()->first());
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return ServiceResponse::success('Password updated successfully');
    }




    public function addProfile(Request $request)
    {
        // $user = User::find(1);
        $user = auth()->user();


        $identifier = Str::random(10);
        $profile = Profiles::create([
            'user_id' => $user->id,
            'identifier' => $identifier,
            'email' => $request->email,
            'phone' => $request->phone,
            'user_address_id' => $request->user_address_id,

        ]);

        return response()->json(['message' => 'Profile added successfully', 'profile' => $profile]);
    }

    public function updateProfile(Request $request)
    {
        // $user = User::find(1);
        $user = auth()->user();

        $profile = Profiles::where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        $identifier = Str::random(10);

        $profile->update([
            'identifier' => $identifier,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $userFields = ['name', 'email', 'password', 'role_id', 'status', 'phone', 'image', 'restaurant_id'];
        $userData = $request->only($userFields);

        if (!empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        if (!empty($userData)) {
            $user->update($userData);
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profile->fresh(),
            'user' => $user->fresh()
        ]);
    }

    public function addUserAddress(Request $request)
    {
        // Use authenticated user ID instead of requiring user_id in request
        $userId = auth()->user()->id;

        $data = $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
        ]);

        // Check if the same address already exists for the user
        $existingAddress = UserAddresses::where('user_id', $userId)
            ->where('address', $data['address'])
            ->where('city', $data['city'])
            ->where('state', $data['state'])
            ->where('country', $data['country'])
            ->first();

        if ($existingAddress) {
            return ServiceResponse::error('This address already exists for the user.');
        }

        // Create new address
        $data['user_id'] = $userId;  // Assign authenticated user ID
        UserAddresses::create($data);

        // Fetch all addresses for the user
        $addresses = UserAddresses::where('user_id', $userId)->get();

        return ServiceResponse::success('User address added successfully', ['addresses' => $addresses]);
    }


    // make a similar to update address
    // and deleteUserAddress

    public function updateUserAddress(Request $request, $id)
    {
        // Validate request (removed 'id' validation)
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
        ]);

        // Find the address and ensure it belongs to the correct user
        $existingAddress = UserAddresses::where('id', $id)->where('user_id', $data['user_id'])->first();

        if (!$existingAddress) {
            return ServiceResponse::error('Address not found or unauthorized');
        }

        $existingAddress->update($data);

        // Fetch all addresses for the user
        $addresses = UserAddresses::where('user_id', $data['user_id'])->get();

        return ServiceResponse::success('User address updated successfully', ['addresses' => $addresses]);
    }

    
    public function deleteUserAddress($id)
    {
        // Find the address and ensure it belongs to the correct user
        $existingAddress = UserAddresses::where('id', $id)->first();    

        if (!$existingAddress) {
            return ServiceResponse::error('Address not found or unauthorized');
        }
        $existingAddress->delete();
        // Fetch all addresses for the user
        $addresses = UserAddresses::where('user_id', $existingAddress->user_id)->get();

        return ServiceResponse::success('User address deleted successfully', ['addresses' => $addresses]);
    }




    public function getUserAddresses(Request $request)
    {   
        $id = auth()->user()->id;
        // Fetch all addresses for the user
        $addresses = UserAddresses::where('user_id', $id)->get();
        return ServiceResponse::success('User addresses added successfully', ['addresses' => $addresses]);
    }
}
