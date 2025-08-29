<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderReportResource;
use App\Models\Order;
use App\Helpers\ServiceResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderReportController extends Controller
{
    public function daily(Request $request)
    {
        // Prefer explicit date param, fallback to filters['report-date'], then today
        $date = $request->input('date', null);

        $filtersInput = $request->filled('filters') ? $request->input('filters') : null;
        $filters = $filtersInput ? json_decode($filtersInput, true) : null;

        if (!$date && !empty($filters['report-date'])) {
            $date = $filters['report-date'];
        }
        $date = $date ?? Carbon::today()->toDateString();

        // Start base query
        $orders = Order::with(['customer', 'restaurant', 'orderProducts.product'])
            ->whereDate('created_at', $date);

        // Handle restaurant filter
        if ($request->filled('restaurant_id')) {
            $orders->where('restaurant_id', $request->restaurant_id);
        }

        // Determine orderScope (filters take precedence)
        $orderScope = null;
        if (!empty($filters['orderScope'])) {
            $orderScope = $filters['orderScope'];
        } elseif ($request->filled('orderScope')) {
            $orderScope = $request->orderScope;
        }

        // Apply scope: normal (default), deleted (only soft-deleted), all-orders (include trashed)
        if ($orderScope === 'deleted') {
            $orders->onlyTrashed();
        } elseif ($orderScope === 'all-orders') {
            $orders->withTrashed();
        }
        // else 'normal' or null => default behaviour (exclude trashed)

        // Handle filters JSON (order id / type / status)
        if ($filters) {
            // support both 'orderid' and 'order_id'
            if (!empty($filters['orderid']) || !empty($filters['order_id'])) {
                $orderIdValue = $filters['orderid'] ?? $filters['order_id'];
                $orders->where('order_number', 'LIKE', '%' . $orderIdValue . '%');
            }

            if (!empty($filters['type'])) {
                // use like for flexibility
                $orders->where('order_type', 'LIKE', '%' . $filters['type'] . '%');
            }

            if (!empty($filters['status'])) {
                $orders->where('status', $filters['status']);
            }
        }

        $orders = $orders->get();

        return ServiceResponse::success('Daily Order report fetched successfully', [
            'date' => $date,
            'orders' => OrderReportResource::collection($orders),
            'totals' => $this->getTotals($orders),
        ]);
    }


    public function monthly(Request $request)
    {
        $filters = $request->input('filters', null);

        $query = Order::with(['customer', 'restaurant', 'orderProducts.product'])
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month);

        // Apply filters
        $this->applyFilters($query, $filters);

        $orders = $query->get();

        return ServiceResponse::success('Monthly report fetched successfully', [
            'month' => Carbon::now()->month,
            'year' => Carbon::now()->year,
            'orders' => OrderReportResource::collection($orders),
            'totals' => $this->getTotals($orders),
        ]);
    }

    /**
     * Common filters logic
     */
    private function applyFilters($query, $filters)
    {
        if (!$filters) {
            return;
        }

        $filters = is_string($filters) ? json_decode($filters, true) : $filters;

        if (isset($filters['order_id']) && !empty($filters['order_id'])) {
            $query->where('order_number', 'like', '%' . $filters['order_id'] . '%');
        }

        if (isset($filters['type']) && !empty($filters['type'])) {
            $query->where('order_type', 'like', '%' . $filters['type'] . '%');
        }

        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
    }

    private function getTotals($orders)
    {
        return [
            'total_sale' => $orders->sum('total_price'),
            'tips' => $orders->sum('tips'),
            'total_tax' => $orders->sum('tax_amount'),
            'total_discount' => $orders->sum('discount_value'),
            'grand_total' => $orders->sum('final_total'),
        ];
    }
}
