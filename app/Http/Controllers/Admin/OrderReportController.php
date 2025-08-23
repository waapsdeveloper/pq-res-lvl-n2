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
        $date = $request->date ?? Carbon::today()->toDateString();

        $orders = Order::with(['customer', 'restaurant', 'orderProducts.product'])
            ->whereDate('created_at', $date);

        // Handle restaurant filter
        if ($request->filled('restaurant_id')) {
            $orders->where('restaurant_id', $request->restaurant_id);
        }

        // Handle filters JSON
        if ($request->filled('filters')) {
            $filters = json_decode($request->filters, true);

            if (!empty($filters['orderid'])) {
                $orders->where('order_number', 'LIKE', '%' . $filters['orderid'] . '%');
            }

            if (!empty($filters['type'])) {
                $orders->where('order_type', $filters['type']);
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
