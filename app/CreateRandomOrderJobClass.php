<?php

namespace App;

use App\Helpers\ServiceResponse;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payments;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class CreateRandomOrderJobClass
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}
    public function __invoke()
    {
        $productIds = Product::whereBetween('id', [1, 100])
            ->inRandomOrder()
            ->take(rand(1, 6))
            ->pluck('id');

        if ($productIds->isEmpty()) {
            return ServiceResponse::error("No products available to create a random order.");
        }

        $products = Product::whereIn('id', $productIds)->get();

        $customer_id = Arr::random([10, 11, 12, 13, 14, 15, '']);

        $totalPrice = 0;
        $orderProducts = [];

        foreach ($products as $product) {
            $quantity = rand(1, 6);
            $price = $product->price;
            $itemTotal = $price * $quantity;

            $totalPrice += $itemTotal;

            $orderProducts[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
                'notes' => 'Random order note',
            ];
        }

        $discount = rand(0, 10);
        $finalPrice = max(0, $totalPrice - $discount);
        $types = ['dine-in', 'take-away', 'delivery', 'drive-thru', 'curbside-pickup', 'catering', 'reservation'];
        $statuses = ['pending', 'confirmed', 'preparing', 'ready_for_pickup', 'out_for_delivery', 'delivered', 'completed'];


        $type = Arr::random($types);
        $status = Arr::random($statuses);

        // Generate a random date
        $startYear = 2022;
        $endYear = Carbon::now()->year;  // Current year
        $randomYear = rand($startYear, $endYear);
        $randomMonth = rand(1, 12);
        $randomDay = rand(1, Carbon::create($randomYear, $randomMonth, 1)->daysInMonth);
        $randomDate = Carbon::create($randomYear, $randomMonth, $randomDay)
            ->addHours(rand(0, 23))
            ->addMinutes(rand(0, 59));
        $order = Order::create([
            'identifier' => 'ORD-' . uniqid(),
            'order_number' => strtoupper(uniqid('ORD-')),
            'type' => $type,
            'status' => $status,
            'notes' => 'This is a randomly generated order.',
            'customer_id' => $customer_id,
            'discount' => $discount,
            'invoice_no' => strtoupper(uniqid('INV-')),
            'table_no' => rand(1, 20),
            'total_price' => $finalPrice,
            'restaurant_id' => 1,
            'order_at' => $randomDate,


        ]);

        foreach ($orderProducts as $orderProduct) {
            $order->orderProducts()->create($orderProduct);
        }
        $invoiceStatuses = Arr::random(['pending', 'received']);
        $paymentMethod = Arr::random(['cash', 'card', 'transfer']);
        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_no' => $order->invoice_no,
            'invoice_date' => $randomDate,
            'payment_method' => $paymentMethod,
            'total' => $order->total_price,
            'status' =>  $invoiceStatuses,
            'notes' => "This is a randomly generated $order->invoice_no invoice.",

        ]);

        $paymentStatuses = Arr::random(['pending', 'received']);
        $paymentMode = Arr::random(['cash', 'card', 'transfer']);
        $paymentPortal = Arr::random(['cash', 'stripe', 'paypal']);
        $payment = Payments::create([
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'customer_id' => $customer_id,
            'payment_status' => $paymentStatuses,
            'payment_mode' => $paymentMode,
            'payment_portal' => $paymentPortal,
        ]);


        return ServiceResponse::success("Random order created successfully", [
            'order' => new OrderResource($order),
        ]);
    }
}
