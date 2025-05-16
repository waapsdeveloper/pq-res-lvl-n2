<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use App\Http\Resources\Admin\ExpenseCategoryResource;
use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);

        $query = ExpenseCategory::with('restaurant')->orderBy('id', 'desc');

        if ($search) {
            $query->where('category_name', 'like', '%' . $search . '%');
        }

        if ($filters) {
            $filters = json_decode($filters, true);
            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (isset($filters['restaurant_id'])) {
                $query->where('restaurant_id', $filters['restaurant_id']);
            }
            // Apply filter for expense category name using 'expense_name' key inside filters
            if (isset($filters['expense_name']) && !empty($filters['expense_name'])) {
                $query->where('category_name', 'like', '%' . $filters['expense_name'] . '%');
            }
        }

        $data = $query->paginate($perpage, ['*'], 'page', $page);

        $data->getCollection()->transform(function ($item) {
            return new ExpenseCategoryResource($item);
        });

        return ServiceResponse::success("Expense Category list successfully", ['data' => $data]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'category_name' => 'required|string|max:255',
            'daily_estimate' => 'nullable|numeric',
            'weekly_estimate' => 'nullable|numeric',
            'monthly_estimate' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $data = $validator->validated();

        if (isset($data['image'])) {
            $data['image'] = Helper::getBase64ImageUrl($data['image'], 'expense-category');
        }

        $expenseCategory = ExpenseCategory::create($data);

        return ServiceResponse::success('Expense Category created successfully', ['expense_category' => new ExpenseCategoryResource($expenseCategory)]);
    }

    public function show($id)
    {
        $expenseCategory = ExpenseCategory::with('restaurant')->find($id);
        if (!$expenseCategory) {
            return ServiceResponse::error('Expense Category not found', 404);
        }
        return ServiceResponse::success('Expense Category details retrieved', ['expense_category' => new ExpenseCategoryResource($expenseCategory)]);
    }

    public function update(Request $request, $id)
    {
        $expenseCategory = ExpenseCategory::find($id);
        if (!$expenseCategory) {
            return ServiceResponse::error('Expense Category not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'category_name' => 'sometimes|required|string|max:255',
            'daily_estimate' => 'nullable|numeric',
            'weekly_estimate' => 'nullable|numeric',
            'monthly_estimate' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $data = $validator->validated();

        if (isset($data['image'])) {
            $data['image'] = Helper::getBase64ImageUrl($data['image'], 'expense-category');
        }

        $expenseCategory->update($data);

        return ServiceResponse::success('Expense Category updated successfully', ['expense_category' => new ExpenseCategoryResource($expenseCategory)]);
    }

    public function destroy($id)
    {
        $expenseCategory = ExpenseCategory::find($id);
        if (!$expenseCategory) {
            return ServiceResponse::error('Expense Category not found', 404);
        }
        $expenseCategory->delete();
        return ServiceResponse::success('Expense Category deleted successfully');
    }
}
