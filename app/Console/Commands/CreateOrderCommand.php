<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Invoice;
use App\Models\Rtable;
use App\Models\OrderLog;
use App\Helpers\Identifier;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CreateOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-order-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quickly create a random order for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $restaurant_id = 1;

        // Get or create a test user
        $customer = User::firstOrCreate(
            ['email' => 'test-customer@demo.com'],
            [
                'name' => 'Test Customer',
                'phone' => '+10000000001',
                'password' => Hash::make('test123$'),
                'role_id' => 10,
                'status' => 'active',
                'restaurant_id' => $restaurant_id,
                'image' => 'images/user/user-1.png',
                'dial_code' => '+1'
            ]
        );

        // Pick 1-3 random products
        $products = Product::where('restaurant_id', $restaurant_id)
            ->inRandomOrder()
            ->take(rand(1, 3))
            ->get();

        if ($products->isEmpty()) {
            $this->error('No products found for restaurant.');
            return;
        }

        $totalPrice = 0;
        $orderProducts = [];
        foreach ($products as $product) {
            $quantity = rand(1, 3);
            $price = $product->price;
            $itemTotal = $price * $quantity;
            $totalPrice += $itemTotal;

            $variation = null;
            if ($product->variation_id && $product->variation && $product->variation->meta_value) {
                $variation = json_decode($product->variation->meta_value, true);
            }

            $orderProducts[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
                'notes' => Arr::random(['Extra cheese', 'No onions', 'Spicy', null]),
                'variation' => $variation,
                'category' => Arr::random(['food', 'drink', 'dessert', null])
            ];
        }

        $discount = mt_rand(0, 500) / 100;
        $type = Arr::random(['dine-in', 'take-away', 'delivery']);
        $orderStatus = Arr::random(['pending', 'confirmed', 'preparing', 'completed']);
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(str()->random(6));
        $orderNote = Arr::random(['Fast delivery', 'Leave at door', null]);
        $paymentMethod = Arr::random(['cash', 'card']);
        $orderType = $type;
        $deliveryAddress = ($type === 'delivery') ? '123 Test St' : null;
        $couponCode = Arr::random(['', 'DISCOUNT10', null]);
        $discountValue = $discount;
        $finalTotal = $totalPrice - $discount;
        $taxPercentage = mt_rand(500, 1500) / 100;
        $taxAmount = $finalTotal * ($taxPercentage / 100);
        $tipsAmount = mt_rand(0, 200) / 100;
        $tips = $tipsAmount;
        $deliveryCharges = ($type === 'delivery') ? mt_rand(100, 300) / 100 : 0;

        $table = Rtable::inRandomOrder()->first();
        $tableNo = $table ? $table->id : null;

        $finalPrice = $totalPrice - $discount + $taxAmount + $tipsAmount + $deliveryCharges;

        $order = Order::create([
            'identifier' => 'ORD-',
            'restaurant_id' => $restaurant_id,
            'order_number' => $orderNumber,
            'type' => $type,
            'status' => $orderStatus,
            'notes' => $orderNote,
            'customer_id' => $customer->id ?? null,
            'discount' => $discount,
            'invoice' => 'INV-',
            'table_no' => $tableNo,
            'total_price' => $finalPrice,
            'payment_method' => $paymentMethod,
            'order_type' => $orderType,
            'delivery_address' => $deliveryAddress,
            'coupon_code' => $couponCode,
            'discount_value' => $discountValue,
            'final_total' => $finalTotal,
            'tax_percentage' => $taxPercentage,
            'tax_amount' => $taxAmount,
            'tips' => $tips,
            'source' => Arr::random(['pos', 'website']),
            'tips_amount' => $tipsAmount,
            'delivery_charges' => $deliveryCharges,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $order->update([
            'identifier' => Identifier::make('Order', $order->id, 3),
            'invoice_no' => Identifier::make('Invoice', $order->id, 3) . '-' . $order->order_number . '-' . $order->id . '-' . now()->format('Ymd')
        ]);

        foreach ($orderProducts as $orderProduct) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $orderProduct['product_id'],
                'quantity' => $orderProduct['quantity'],
                'price' => $orderProduct['price'],
                'notes' => $orderProduct['notes'] ?? null,
                'variation' => json_encode($orderProduct['variation']),
                'category' => $orderProduct['category'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $order->load('orderProducts.product');

        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_no' => $order->invoice_no,
            'invoice_date' => now(),
            'restaurant_id' => $restaurant_id,
            'payment_method' => $paymentMethod,
            'total' => $order->total_price,
            'status' => 'pending',
            'notes' => ""
        ]);

        // Log order_created with product assignment (new_value = full products array)
        $performer = User::where('role_id', 1)->first(); // Super Admin or System
        $buildProductPayload = function ($op) {
            $productId = $op->product_id ?? null;
            $prodModel = $op->product ?? ($productId ? Product::find($productId) : null);
            $productName = $prodModel ? $prodModel->name : null;
            $quantity = $op->quantity ?? null;
            $price = $op->price ?? null;
            $notes = $op->notes ?? null;
            $variationRaw = $op->variation ?? null;
            if (is_string($variationRaw) && $variationRaw !== '') {
                $decoded = json_decode($variationRaw, true);
                $variation = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $variationRaw;
            } else {
                $variation = $variationRaw;
            }
            $selectedVariations = [];
            if (is_array($variation)) {
                foreach ($variation as $group) {
                    if (isset($group['selectedOption']) && $group['selectedOption']) {
                        $selectedVariations[] = [
                            'type' => $group['type'] ?? null,
                            'selected' => $group['selectedOption'],
                        ];
                        continue;
                    }
                    if (isset($group['options']) && is_array($group['options'])) {
                        $sel = [];
                        foreach ($group['options'] as $opt) {
                            if ((isset($opt['selected']) && $opt['selected']) || (isset($opt['is_selected']) && $opt['is_selected'])) {
                                $sel[] = $opt;
                            }
                        }
                        if (!empty($sel)) {
                            $selectedVariations[] = [
                                'type' => $group['type'] ?? null,
                                'selected' => $sel,
                            ];
                        }
                    }
                }
            }
            return [
                'product_id' => $productId,
                'name' => $productName,
                'quantity' => $quantity,
                'price' => $price,
                'notes' => $notes,
                'variation' => $variation,
                'selected_variations' => $selectedVariations,
            ];
        };
        $productPayloads = [];
        foreach ($order->orderProducts as $op) {
            $productPayloads[] = $buildProductPayload($op);
        }
        OrderLog::create([
            'order_id' => $order->id,
            'event_type' => 'order_created',
            'old_value' => null,
            'new_value' => json_encode($productPayloads, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'performed_by' => $performer ? $performer->name : 'System',
            'performed_by_id' => $performer ? $performer->id : null,
        ]);
        OrderLog::create([
            'order_id'        => $order->id,
            'event_type'      => 'status_change',
            'old_value'       => null,
            'new_value'       => $order->status,
            'performed_by'    => $performer ? $performer->name : 'System',
            'performed_by_id' => $performer ? $performer->id : null,
        ]);
        OrderLog::create([
            'order_id'        => $order->id,
            'event_type'      => 'payment_status',
            'old_value'       => null,
            'new_value'       => $order->is_paid ? 'Paid' : 'Unpaid',
            'performed_by'    => $performer ? $performer->name : 'System',
            'performed_by_id' => $performer ? $performer->id : null,
        ]);

        $this->info('Random order created successfully.');
    }
}
