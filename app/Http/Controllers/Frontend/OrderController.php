<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\AddOrderBookingResource;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function makeOrderBookings(Request $request, $rtableIdf = null)
    {
        // $data = $request->validated();
        $data = $request->all();
        $customer = $request->input('customer', null);

        if (!empty($customer)) {
            $customerName = $data['customer_name'];
            $customerPhone = $data['customer_phone'];
            $customerPhone = $data['customer_email'];

            $user = User::where('phone', $customerPhone)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $customerName,
                    'phone' => $customerPhone,
                    'email' => $customerPhone . "@phone.text",
                ]);
            }
        } else {
            $user = null;
        }


        $totalPrice = 0;
        $orderProducts = [];
        foreach ($data['products'] as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                continue;
                // return ServiceResponse::error("Product with ID {$item['product_id']} not found.");
            }

            // $pricePerUnit = $product->price;
            $pricePerUnit = $item['price'];

            $quantity = $item['quantity'];
            $itemTotal = $pricePerUnit * $quantity;

            $totalPrice += $itemTotal;

            $orderProducts[] = [
                'product_id' => $item['product_id'],
                'quantity' => $quantity,
                'price' => $pricePerUnit,
                'notes' => $item['notes'] ?? null,
            ];
        }

        $discount = $data['discount'] ?? 0;
        $type = $data['type'] ?? null;
        $tableNo = $data['tableNo'] ?? null;
        // $finalPrice = $totalPrice - ($totalPrice * ($discount / 100));
        $finalPrice = $totalPrice - $discount;
        // return response()->json($finalPrice);
        $orderNumber = strtoupper(uniqid('ORD-'));
        $orderNote = $request->notes;
        $orderStatus = $request->status;

        $order = Order::create([
            'identifier' => $rtableIdf ?? null,
            'order_number' => $orderNumber,
            'type' =>  !empty($rtableIdf) ? 'dine-in' : '',
            'status' => $orderStatus,
            'notes' => $orderNote,
            'customer_id' => $user->id ?? 0,
            'discount' => $discount,
            'invoice' => 'INV-' . uniqid(),
            'table_no' => $tableNo,
            'total_price' => $finalPrice,
            'order_at' => now(),
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
}
