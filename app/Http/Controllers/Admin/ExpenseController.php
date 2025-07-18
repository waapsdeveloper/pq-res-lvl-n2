<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Http\Resources\Admin\ExpenseResource;
use App\Helpers\Helper;
use App\Helpers\ServiceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->input('filters', null);
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);

        $query = Expense::with('category')->orderBy('id', 'desc');

        if ($filters) {
            $filters = json_decode($filters, true);
            if (!empty($filters['expense'])) {
                $query->where('name', 'like', '%' . $filters['expense'] . '%');
            }
            // Date range filter
            if (!empty($filters['started_from']) && !empty($filters['ended_at'])) {
                $query->whereBetween('date', [$filters['started_from'], $filters['ended_at']]);
            } else {
                if (!empty($filters['started_from'])) {
                    $query->whereDate('date', '>=', $filters['started_from']);
                }
                if (!empty($filters['ended_at'])) {
                    $query->whereDate('date', '<=', $filters['ended_at']);
                }
            }
            if (!empty($filters['type'])) {
                $query->where('type', $filters['type']);
            }
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (!empty($filters['category'])) {
                $query->whereHas('category', function ($q) use ($filters) {
                    $q->where('category_name', 'like', '%' . $filters['category'] . '%');
                });
            }
        }

        // Calculate total amount before pagination
        $totalAmount = (clone $query)->sum('amount');

        $data = $query->paginate($perpage, ['*'], 'page', $page);

        $data->getCollection()->transform(function ($item) {
            return new ExpenseResource($item);
        });

        return ServiceResponse::success("Expense list successfully", [
            'total_amount' => $totalAmount,
            'data' => $data
        ]);
    }

    public function store(Request $request)
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'type' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|in:paid,unpaid',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $data = $validator->validated();

        if (isset($data['image'])) {
            $data['image'] = Helper::getBase64ImageUrl($data['image'], 'expense');
        }

        $expense = Expense::create($data);

        return ServiceResponse::success('Expense created successfully', ['expense' => new ExpenseResource($expense)]);
    }

    public function show($id)
    {
        $expense = Expense::with('category')->find($id);
        if (!$expense) {
            return ServiceResponse::error('Expense not found', 404);
        }
        return ServiceResponse::success('Expense details retrieved', ['expense' => new ExpenseResource($expense)]);
    }

    public function update(Request $request, $id)
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
        $expense = Expense::find($id);
        if (!$expense) {
            return ServiceResponse::error('Expense not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric',
            'expense_category_id' => 'sometimes|required|exists:expense_categories,id',
            'type' => 'sometimes|required|string',
            'date' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:paid,unpaid',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $data = $validator->validated();

        // Handle image update logic similar to RestaurantController
        if (array_key_exists('image', $data) && $data['image']) {
            // Delete old image if exists
            if ($expense->image) {
                Helper::deleteImage($expense->image);
            }
            $data['image'] = Helper::getBase64ImageUrl($data['image'], 'expense');
        } else {
            // Keep the previous image if not provided
            $data['image'] = $expense->image;
        }

        $expense->update($data);

        return ServiceResponse::success('Expense updated successfully', ['expense' => new ExpenseResource($expense)]);
    }

    public function destroy($id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return ServiceResponse::error('Expense not found', 404);
        }
        $expense->delete();
        return ServiceResponse::success('Expense deleted successfully');
    }

    // Additional API for updating status
    public function updateStatus(Request $request, $id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return ServiceResponse::error('Expense not found', 404);
        }
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:paid,unpaid',
        ]);
        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }
        $expense->status = $request->status;
        $expense->save();
        return ServiceResponse::success('Expense status updated successfully', ['expense' => new ExpenseResource($expense)]);
    }

    // Additional API for updating type
    public function updateType(Request $request, $id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return ServiceResponse::error('Expense not found', 404);
        }
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:recurring,other',
        ]);
        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }
        $expense->type = $request->type;
        $expense->save();
        return ServiceResponse::success('Expense type updated successfully', ['expense' => new ExpenseResource($expense)]);
    }
}
