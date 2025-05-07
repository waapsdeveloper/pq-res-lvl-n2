<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BranchConfig;
use App\Models\Currency;
use Illuminate\Http\Request;

class BranchConfigController extends Controller
{
    public function index()
    {
        $configs = BranchConfig::with('branch')->get();
        return response()->json(['configs' => $configs]);
    }

    public function create()
    {
        $currencies = Currency::all(); // Optional if using a separate currencies table
        return response()->json(['currencies' => $currencies]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'branch_id' => 'required|exists:restaurants,id',
            'tax' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|max:3',
        ]);

        $config = BranchConfig::create($data);
        return response()->json(['message' => 'Branch configuration created successfully', 'config' => $config]);
    }

    public function show($id)
    {
        $config = BranchConfig::with('branch')->findOrFail($id);
        return response()->json(['config' => $config]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'tax' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|max:3',
        ]);

        $config = BranchConfig::findOrFail($id);
        $config->update($data);

        return response()->json(['message' => 'Branch configuration updated successfully', 'config' => $config]);
    }

    public function destroy($id)
    {
        $config = BranchConfig::findOrFail($id);
        $config->delete();

        return response()->json(['message' => 'Branch configuration deleted successfully']);
    }
}
