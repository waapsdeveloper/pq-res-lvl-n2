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
        // dd($request->all());
        // Validate the request data
        $validated = $request->validate([
            'phone' => 'required', // Ensure phone is mandatory
        ]);
        // dd($validated);

        $data = $request->all();
        $phone = $data['phone'];
        $rtableIdf = $request->input('table_identifier', null);
        $customer = User::where('phone', $phone)->first();  // Search for the customer by phone
        $customerId = null;
        if (!$customer) {
            // If no customer found, create a new "walk-in-customer"
            $customer = User::create([
                'name' => 'walk-in-customer',
                'phone' => $phone,
                'email' => $phone . '@domain.com',  // Use a default or dynamic email
                'role_id' => 0,  // Default role for walk-in customers
            ]);
            $customerId = $customer->id;  // Use the newly created customer's ID
        } else {
            // If customer found, get their ID
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
            ];
        }

        $discount = $data['discount'] ?? 0;
        // $type = $data['type'];
        $tableNo = $data['tableNo'] ?? null;
        $finalPrice = $totalPrice - $discount;
        $orderNumber = strtoupper(uniqid('ORD-'));
        $orderNote = $request->notes;
        $orderStatus = $request->status;

        if ($rtableIdf) {
            $type = 'dine-in';
        } else {
            $type = $data['type'];
        }

        $order = Order::create([
            'identifier' => $rtableIdf ?? null,
            'order_number' => $orderNumber,

            'type' => $type,
            'status' => $orderStatus,
            'notes' => $orderNote,
            'customer_id' => $customerId,
            'discount' => $discount,
            'invoice' => 'INV-' . uniqid(),
            'table_no' => $tableNo,
            'total_price' => $finalPrice,
            'restaurant_id' => $restaurant_id ?? 1,
        ]);

        foreach ($orderProducts as $orderProduct) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $orderProduct['product_id'],
                'quantity' => $orderProduct['quantity'],
                'price' => $orderProduct['price'],
                'notes' => $orderProduct['notes'] ?? null,
            ]);
        }

        $order->load('orderProducts.product');

        $data = new AddOrderBookingResource($order);

        return ServiceResponse::success("Order list successfully", ['data' => $data]);
    }

    public function getOrderBookings(Request $request)
    {
        $orders = Order::with('orderProducts.product')->get();
        $data = AddOrderBookingResource::collection($orders);
        return ServiceResponse::success("Order list successfully", ['data' => $data]);
    }
}
