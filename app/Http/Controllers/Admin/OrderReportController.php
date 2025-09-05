<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderReportResource;
use App\Http\Resources\Admin\ProductDailyReportResource;
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
        // base totals
        $totalSale = $orders->sum('total_price');
        $tips = $orders->sum('tips');
        $totalTax = $orders->sum('tax_amount');
        $totalDiscount = $orders->sum('discount_value');
        $grandTotal = $orders->sum('final_total');

        // normalize payment method and compute counts & sums
        $cardOrders = $orders->filter(function ($o) {
            $pm = strtolower(trim($o->payment_method ?? ''));
            return $pm !== '' && strpos($pm, 'creditcard') !== false;
        });

        $cashOrders = $orders->filter(function ($o) {
            $pm = strtolower(trim($o->payment_method ?? ''));
            return $pm !== '' && strpos($pm, 'cash') !== false;
        });

        $totalCardCount = $cardOrders->count();
        $totalCardAmount = $cardOrders->sum(function ($o) {
            return $o->final_total ?? 0;
        });

        $totalCashCount = $cashOrders->count();
        $totalCashAmount = $cashOrders->sum(function ($o) {
            return $o->final_total ?? 0;
        });

        return [
            'total_sale' => $totalSale,
            'tips' => $tips,
            'total_tax' => $totalTax,
            'total_discount' => $totalDiscount,
            'grand_total' => $grandTotal,
            // card metrics (creditcard)
            'total_card_count' => $totalCardCount,
            'total_card_amount' => $totalCardAmount,
            // cash metrics
            'total_cash_count' => $totalCashCount,
            'total_cash_amount' => $totalCashAmount,
        ];
    }



    public function productDailyReport(Request $request)
    {
        // Accept date param, or filters['report-date'], fallback to today
        $date = $request->input('date', null);
        $filtersInput = $request->filled('filters') ? $request->input('filters') : null;
        $filters = $filtersInput ? (is_string($filtersInput) ? json_decode($filtersInput, true) : $filtersInput) : null;

        if (!$date && !empty($filters['report-date'])) {
            $date = $filters['report-date'];
        }
        $date = $date ?? Carbon::today()->toDateString();

        // Base order query for the date
        $ordersQuery = Order::with(['restaurant', 'orderProducts.product.category'])
            ->whereDate('created_at', $date);

        // Restaurant filter
        if ($request->filled('restaurant_id')) {
            $ordersQuery->where('restaurant_id', $request->restaurant_id);
        }

        // orderScope handling (deleted / all-orders / normal)
        $orderScope = null;
        if (!empty($filters['orderScope'])) {
            $orderScope = $filters['orderScope'];
        } elseif ($request->filled('orderScope')) {
            $orderScope = $request->orderScope;
        }
        if ($orderScope === 'deleted') {
            $ordersQuery->onlyTrashed();
        } elseif ($orderScope === 'all-orders') {
            $ordersQuery->withTrashed();
        }

        // Reuse existing order-level filters
        $this->applyFilters($ordersQuery, $filtersInput);

        // Product-level filters applied via whereHas
        if (!empty($filters['product_name'])) {
            $ordersQuery->whereHas('orderProducts.product', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['product_name'] . '%');
            });
        }

        if (!empty($filters['category']) || !empty($filters['category_id'])) {
            $cat = $filters['category'] ?? $filters['category_id'];
            // assumes product->category relation exists
            $ordersQuery->whereHas('orderProducts.product.category', function ($q) use ($cat) {
                $q->where('name', 'like', '%' . $cat . '%');
            });
        }

        if (!empty($filters['variation'])) {
            $ordersQuery->whereHas('orderProducts', function ($q) use ($filters) {
                $q->where('variation', 'like', '%' . $filters['variation'] . '%')
                    ->orWhere('variation_name', 'like', '%' . $filters['variation'] . '%');
            });
        }

        // Fetch orders that match
        $orders = $ordersQuery->get();

        // Build flat rows from orderProducts
        $rows = collect();
        foreach ($orders as $order) {
            foreach ($order->orderProducts as $op) {
                $product = $op->product ?? null;

                $productId = $product->id ?? ($op->product_id ?? null);
                $productName = $product->name ?? ($op->product_name ?? $op->name ?? null);
                $categoryName = $product && isset($product->category) ? ($product->category->name ?? null) : ($op->category_name ?? null);
                $variationName = $this->formatVariationName($op);

                $quantity = (float) ($op->quantity ?? $op->qty ?? 0);
                $unitPrice = (float) ($op->unit_price ?? $op->price ?? 0);
                $totalPrice = (float) ($op->total_price ?? ($unitPrice * $quantity));
                $tax = (float) ($op->tax_amount ?? 0);
                $discount = (float) ($op->discount_value ?? 0);

                $rows->push([
                    'order_id' => $order->id,
                    'order_number' => $order->order_number ?? null,
                    'order_time' => $order->created_at ? $order->created_at->toDateTimeString() : null,
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'category' => $categoryName,
                    'variation' => $variationName,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'tax' => $tax,
                    'discount' => $discount,
                ]);
            }
        }

        // Optionally: allow min/max quantity filters on aggregated product rows
        $grouped = $rows->groupBy('product_id')->map(function ($group, $productId) {
            $first = $group->first();
            return [
                'product_id' => $productId,
                'product_name' => $first['product_name'] ?? null,
                'category' => $first['category'] ?? null,
                'total_quantity' => $group->sum('quantity'),
                'total_sales' => $group->sum('total_price'),
                'total_tax' => $group->sum('tax'),
                'total_discount' => $group->sum('discount'),
                'unit_price_sample' => $first['unit_price'] ?? 0,
                'rows' => $group->values(),
            ];
        })->values();
        // Apply min/max quantity filter on aggregated groups if requested
        if (!empty($filters['min_quantity']) || !empty($filters['max_quantity'])) {
            $min = !empty($filters['min_quantity']) ? (float) $filters['min_quantity'] : null;
            $max = !empty($filters['max_quantity']) ? (float) $filters['max_quantity'] : null;
            $grouped = $grouped->filter(function ($g) use ($min, $max) {
                if (!is_null($min) && $g['total_quantity'] < $min)
                    return false;
                if (!is_null($max) && $g['total_quantity'] > $max)
                    return false;
                return true;
            })->values();
        }

        $totals = [
            'total_quantity' => $rows->sum('quantity'),
            'total_sales' => $rows->sum('total_price'),
            'total_tax' => $rows->sum('tax'),
            'total_discount' => $rows->sum('discount'),
        ];
        return ServiceResponse::success('Daily Product report fetched successfully', [
            'date' => $date,
            'products' => ProductDailyReportResource::collection($grouped),
            'totals' => $totals,
        ]);
    }

    /**
     * Formats the variation name from an order product.
     * It decodes the 'variation' JSON and extracts the names from 'selectedOption'.
     *
     * @param mixed $orderProduct The order product model.
     * @return string|null
     */
    private function formatVariationName($orderProduct): ?string
    {
        // Prefer the pre-formatted name if it exists
        if (!empty($orderProduct->variation_name)) {
            return $orderProduct->variation_name;
        }

        $variationJson = $orderProduct->variation ?? null;
        if (!$variationJson) {
            return null;
        }

        $decodedVariations = json_decode($variationJson, true);
        if (!is_array($decodedVariations)) {
            // Return the raw value if it's not valid JSON
            return $variationJson;
        }

        $selectedOptionNames = [];
        foreach ($decodedVariations as $variationGroup) {
            if (isset($variationGroup['selectedOption']) && is_array($variationGroup['selectedOption']) && !empty($variationGroup['selectedOption']['name'])) {
                $selectedOptionNames[] = $variationGroup['selectedOption']['name'];
            }
        }

        return !empty($selectedOptionNames) ? implode(', ', $selectedOptionNames) : null;
    }
}

