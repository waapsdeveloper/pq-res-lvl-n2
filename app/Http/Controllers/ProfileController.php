<?php

namespace App\Http\Controllers;

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

    public function updateUser(Request $request){
        
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);

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
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
        ]);

        // Check if the same address already exists for the user
        $existingAddress = UserAddresses::where([
            'user_id' => $data['user_id'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'country' => $data['country'],
        ])->first();

        if ($existingAddress) {
            return self::success("This address already exists for the user.", ['result' => $existingAddress]);
        }

        $address = UserAddresses::create($data);
        return self::success("User addresses retrieved successfully", ['result' => $address]);
    }

    public function getUserAddresses($id)
    {
        if (!User::where('id', $id)->exists()) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Fetch all addresses for the user
        $addresses = UserAddresses::where('user_id', $id)->get();
        return self::success("User addresses retrieved successfully", ['addresses' => $addresses]);
    }
}
