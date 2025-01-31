<?php

namespace App;

use App\Helpers\Helper;
use App\Helpers\Identifier;
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
    public function __construct() {}

    public function __invoke()
    {
        logger()->info('Job execution started.');

        // Random Date Generation
        $startDate = Carbon::now()->subDays(45);
        $endDate = Carbon::now();
        $randomYear = rand($startDate->year, $endDate->year);
        $randomMonth = rand($startDate->month, $endDate->month);

        if ($randomYear == $endDate->year && $randomMonth > $endDate->month) {
            $randomMonth = $endDate->month;
        }

        $daysInMonth = Carbon::create($randomYear, $randomMonth, 1)->daysInMonth;
        $randomDate = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $randomDay = Carbon::create($randomYear, $randomMonth, $day)
                ->addHours(rand(0, 23))
                ->addMinutes(rand(0, 59));
            $randomDate[] = $randomDay->toDateTimeString();
        }

        $randomDate = Arr::random($randomDate);
        logger()->info('Random date generated', ['random_date' => $randomDate]);

        // Random User Creation or Selection
        $phone = substr(str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT), 0, 10);
        $randomStatus = Arr::random(['active', 'active', 'inactive']);
        $createNewUser = Arr::random([true, false, true]);
        $restaurant_id = Arr::random([1, 2]);

        if ($createNewUser) {
            $customer = User::create([
                'name' => 'walk-in-customer',
                'phone' => "+" . $phone,
                'email' => $phone . '@domain.com',
                'role_id' => 0,
                'restaurant_id' => $restaurant_id,
                'password' => Hash::make('admin123$'),
                'status' => $randomStatus,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
            logger()->info('New user created', ['user_id' => $customer->id]);
        } else {
            $customer = User::inRandomOrder()->first();
            logger()->info('Random user selected', ['user_id' => $customer->id ?? 'N/A']);
        }

        // Fetch Random Products
        $productIds = Product::where('restaurant_id', $restaurant_id)
            ->whereBetween('id', $restaurant_id == 1 ? [1, 15] : [16, 25])
            ->inRandomOrder()
            ->take(rand(1, 4))
            ->pluck('id');

        if ($productIds->isEmpty()) {
            logger()->error('No products available for order creation.');
            return;
        }
        logger()->info('Products fetched', ['product_ids' => $productIds]);

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
        logger()->info('Order products prepared', ['order_products' => $orderProducts]);

        // Order Creation
        $discount = rand(0, 10);
        $finalPrice = max(0, $totalPrice - $discount);
        $type = Arr::random(['dine-in', 'take-away', 'delivery', 'drive-thru', 'curbside-pickup', 'catering', 'reservation']);
        $status = Arr::random(['pending', 'confirmed', 'preparing', 'ready_for_pickup', 'out_for_delivery', 'delivered', 'completed']);
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(str()->random(6));
        $randomNote = Helper::getRandomOrderNote();
        $order = Order::create([
            'identifier' => 'ORD-',
            'order_number' => $orderNumber,
            'type' => $type,
            'status' => $status,
            'notes' => $randomNote,
            'customer_id' => $customer->id,
            'discount' => $discount,
            'invoice_no' => strtoupper(uniqid('INV-')),
            'table_no' => rand(1, 20),
            'total_price' => $finalPrice,
            'restaurant_id' => $restaurant_id,
            'created_at' => $randomDate,
            'updated_at' => $randomDate,
        ]);
        $order->update(
            [
                'identifier' => Identifier::make('Order', $order->id, 3),
                'invoice_no' => Identifier::make('Invoice', $order->id, 3)
            ]
        );
        logger()->info('Order created successfully', ['order_id' => $order->id]);

        foreach ($orderProducts as $orderProduct) {
            $orderProduct['created_at'] = $randomDate;
            $orderProduct['updated_at'] = $randomDate;

            $order->orderProducts()->create($orderProduct);
        }
        logger()->info('Order products saved.');

        // Invoice Creation
        $invoiceStatuses = Arr::random(['pending', 'received']);
        $paymentMethod = Arr::random(['cash', 'card', 'transfer']);
        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_no' => $order->invoice_no,
            'invoice_date' => $randomDate,
            'restaurant_id' => $restaurant_id,
            'payment_method' => $paymentMethod,
            'total' => $order->total_price,
            'status' => $invoiceStatuses,
            'notes' => "Random invoice generated with NO: $order->invoice_no",
            'created_at' => $randomDate,
            'updated_at' => $randomDate,
        ]);
        logger()->info('Invoice created successfully', ['invoice_id' => $invoice->id]);

        // Payment Creation
        $paymentStatuses = Arr::random(['pending', 'received']);
        $paymentMode = Arr::random(['cash', 'card', 'transfer']);
        $paymentPortal = Arr::random(['cash', 'stripe', 'paypal']);
        $payment = Payments::create([
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'customer_id' => $customer->id,
            'payment_status' => $paymentStatuses,
            'payment_mode' => $paymentMode,
            'payment_portal' => $paymentPortal,
            'created_at' => $randomDate,
            'updated_at' => $randomDate,
        ]);
        logger()->info('Payment created successfully', ['payment_id' => $payment->id]);

        logger()->info('Random order job completed successfully.');
    }
}
