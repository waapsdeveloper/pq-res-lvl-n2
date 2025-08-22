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
            ->whereDate('created_at', $date)
            ->get();
        return ServiceResponse::success('Daily Order report fetched successfully', [
             'date' => $date,
            'orders' => OrderReportResource::collection($orders),
            'totals' => $this->getTotals($orders),
        ]);
    }

    public function monthly(Request $request)
    {
        $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year ?? Carbon::now()->year;

        $orders = Order::with(['customer', 'restaurant', 'orderProducts.product'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        return ServiceResponse::success('Monthly report fetch successfully',  [
            'month' => $month,
            'year' => $year,
            'orders' => OrderReportResource::collection($orders),
            'totals' => $this->getTotals($orders),
        ]);
    }

    private function getTotals($orders)
    {
        return [
            'total_sale'    => $orders->sum('total_price'),
            'total_tax'     => $orders->sum('tax_amount'),
            'total_discount'=> $orders->sum('discount_value'),
            'grand_total'   => $orders->sum('final_total'),
        ];
    }
}
