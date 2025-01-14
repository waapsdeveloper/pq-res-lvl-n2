<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\AddOrderBookingResource;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Rtable;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function makeOrderBookings(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'phone' => 'required', // Ensure phone is mandatory
            'table_identifier' => 'nullable'
        ]);
        // return response()->json($request->all());

        $data = $request->all();
        $phone = $data['phone'];
        $rtableIdf = $request->input('table_identifier', null);
        $customer = User::where('phone', $phone)->first();  // Search for the customer by phone
        $customerId = null;
        if (!$customer) {
            $customerNew = User::create([
                'name' => 'walk-in-customer',
                'phone' => $phone,
                'email' => $phone . '@domain.com',  // Use a default or dynamic email
                'role_id' => 0,  // Default role for walk-in customers
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $customerId = $customerNew->id;
        } else {
            $customerId = $customer->id;
        }


        if (!empty($rtableIdf)) {
            $identifier = $rtableIdf;
            $restaurant = Rtable::where('identifier', $identifier)->first();
            if (!$restaurant) {
                return ServiceResponse::error("Invalid table identifier.", [], 400);
            }
            $restaurant_id = $restaurant->id;
        }

        $totalPrice = 0;
        $orderProducts = [];
        foreach ($data['products'] as $item) {
            $product = Product::find($item['id']);
            if (!$product) {
                continue;
            }

            $pricePerUnit = $item['price'];
            $quantity = $item['quantity'];
            $itemTotal = $pricePerUnit * $quantity;
            $totalPrice += $itemTotal;

            $orderProducts[] = [
                'product_id' => $item['id'],
                'quantity' => $quantity,
                'price' => $pricePerUnit,
                'notes' => $item['notes'] ?? null,
                'variation' => $item['variation'] ?? null,

            ];
        }

        $discount = $data['discount'] ?? 0;
        $type = $rtableIdf ? 'dine-in' : $data['type'];
        $tableNo = $data['tableNo'] ?? null;
        $finalPrice = $totalPrice - $discount;
        $uniqid = uniqid();
        $orderNote = $request->notes;
        $orderStatus = $request->status;

        $order = Order::create([
            'identifier' => $rtableIdf ?? null,
            'order_number' => 'ORD-' . $uniqid,
            'type' => $type,
            'status' => $orderStatus,
            'notes' => $orderNote,
            'customer_id' => $customerId,
            'discount' => $discount,
            'invoice_no' => 'INV-' . $uniqid,
            'table_no' => $tableNo,
            'total_price' => $finalPrice,
            'restaurant_id' => $restaurant_id ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        foreach ($orderProducts as $orderProduct) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $orderProduct['product_id'],
                'quantity' => $orderProduct['quantity'],
                'price' => $orderProduct['price'],
                'notes' => $orderProduct['notes'] ?? null,
                'variation' => $orderProduct['variation'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $order->load('orderProducts.product');

        return ServiceResponse::success(['status' => 'Order created successfully', 'data' => $order->order_number]);
    }

    public function trackCustomerOrder(Request $request, $orderId)
    {
        $order = Order::with('orderProducts.product', 'customer', 'restaurant', 'table')
            ->find($orderId);
        $data = new AddOrderBookingResource($order);
        return ServiceResponse::success("Customer Order Tracked Successfully", ['customer_order' => $data]);
    }
}
