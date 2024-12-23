<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\RoleStore;
use App\Http\Requests\Admin\Role\RoleUpdate;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);

        $query = Role::query();
        $query->where('id', '!=', 1);
        // Optionally apply search filter if needed
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Paginate the results
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // dd($data);
        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new RoleResource($item);
        });

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
    public function store(RoleStore $request)
    {
        $data = $request->validated();
        $role = Role::create([
            'name' => $data['name'],
            'slug' => $data['slug'],

        ]);

        return ServiceResponse::success('Roles store successful', ['role' => $role]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        // Attempt to find the restaurant by ID
        $role = Role::find($id);

        // If the restaurant doesn't exist, return an error response
        if (!$role) {
            return ServiceResponse::error("Role $id not found", 404);
        }

        // Return a success response with the restaurant data
        return ServiceResponse::success("Role details retrieved successfully", ['role' => $role]);
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
    public function update(RoleUpdate $request, string $id)
    {
        $data = $request->validated();

        // Find the category by ID
        $role = Role::find($id);

        // If role does not exist
        if (!$role) {
            return ServiceResponse::error("role '$id' not found");
        }

        // Update the role
        $role->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
        ]);

        return ServiceResponse::success('role update successful', ['role' => $role]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);

        // If role does not exist
        if (!$role) {
            return ServiceResponse::error("Role $id not found");
        }
        $role->delete();
        return ServiceResponse::success('Role delete successful');
    }
}
