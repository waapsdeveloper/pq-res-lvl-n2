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
use Illuminate\Support\Facades\Hash;

class CreateRandomOrderJobClass
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}
    public function __invoke()
    {
        $startDate =  Carbon::now()->startOfWeek();
        // $startDate = Carbon::now()->subMonths(1);  // Start from 2 months ago
        $endDate = Carbon::now();  // Up to the current date
        $randomYear = rand($startDate->year, $endDate->year);
        $randomMonth = rand($startDate->month, $endDate->month);
        if ($randomYear == $endDate->year && $randomMonth > $endDate->month) {
            $randomMonth = $endDate->month;
        }
        $daysInMonth = Carbon::create($randomYear, $randomMonth, 1)->daysInMonth;
        $randomDate = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $randomDay = Carbon::create($randomYear, $randomMonth, $day);
            $randomDateSingle = $randomDay
                ->addHours(rand(0, 23))  // Random hour
                ->addMinutes(rand(0, 59));  // Random minute
            $randomDate[] = $randomDateSingle->toDateTimeString();
        }
        $randomDate = Arr::random($randomDate);
        $unique = uniqid(11);
        $randomStatus = Arr::random(['active', 'active', 'inactive']);

        $createNewUser = Arr::random([true, false, true]);

        if ($createNewUser) {
            // Create a new user
            $customer = User::create([
                'name' => 'walk-in-customer',
                'phone' => $unique,
                'email' => $unique . '@domain.com',  // Use a default or dynamic email
                'role_id' => 0,  // Default role for walk-in customers
                'password' => Hash::make('admin123$'),  // Default password for walk-in customers
                'status' => $randomStatus,
                'created_at' => $randomDate,
                'updated_at' => $randomDate, // Set fake created_at date
            ]);
        } else {
            // Select a random existing customer
            $customer = User::inRandomOrder()->first();  // Get a random user from the database
        }

        $productIds = Product::whereBetween('id', [1, 100])
            ->inRandomOrder()
            ->take(rand(1, 4))
            ->pluck('id');

        if ($productIds->isEmpty()) {
            return ServiceResponse::error("No products available to create a random order.");
        }

        $products = Product::whereIn('id', $productIds)->get();


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


        $order = Order::create([
            'identifier' => 'ORD-' . uniqid(),
            'order_number' => strtoupper(uniqid('ORD-')),
            'type' => $type,
            'status' => $status,
            'notes' => 'This is a randomly generated order.',
            'customer_id' =>  $customer->id,
            'discount' => $discount,
            'invoice_no' => strtoupper(uniqid('INV-')),
            'table_no' => rand(1, 20),
            'total_price' => $finalPrice,
            'restaurant_id' => 1,
            'created_at' => $randomDate,
            'updated_at' => $randomDate,


        ]);
        foreach ($orderProducts as $orderProduct) {
            $orderProduct['created_at'] = $randomDate;
            $orderProduct['updated_at'] = $randomDate;

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
            'created_at' => $randomDate,
            'updated_at' => $randomDate,

        ]);

        $paymentStatuses = Arr::random(['pending', 'received']);
        $paymentMode = Arr::random(['cash', 'card', 'transfer']);
        $paymentPortal = Arr::random(['cash', 'stripe', 'paypal']);
        $payment = Payments::create([
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'customer_id' =>  $customer->id,
            'payment_status' => $paymentStatuses,
            'payment_mode' => $paymentMode,
            'payment_portal' => $paymentPortal,
            'created_at' => $randomDate,
            'updated_at' => $randomDate,
        ]);


        return ServiceResponse::success(
            "Random order created successfully",
            // [
            //     'order' => new OrderResource($order),
            // ]
        );
    }
}
