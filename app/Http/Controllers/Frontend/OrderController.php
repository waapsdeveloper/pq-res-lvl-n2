<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Helpers\Identifier;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\OrderBooking\MakeOrderBooking;
use App\Http\Resources\Admin\NotifyResource;
use App\Http\Resources\Frontend\AddOrderBookingResource;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Rtable;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Traits\NotificationTrait;
use App\Traits\Traits\Frontend\CustomerTrait;
use App\Traits\Traits\Frontend\TableBookingTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    use CustomerTrait, TableBookingTrait, NotificationTrait;

    public function makeOrderBookings(MakeOrderBooking $request)
    {
        $data = $request->validated();
        // $data = $request->all();
        $phone = $data['phone'];
        $customer = $this->getCustomerByPhone($phone);

        $rtableIdf = $request->input('table_identifier', null);
        $restaurant = '';
        if (!empty($rtableIdf)) {
            $restaurantId = $this->tableIdentifier($rtableIdf);
        } else {
            $restaurantId = $request->restaurant_id;
        }


        // $totalPrice = 0;
        $orderProducts = [];

        foreach ($data['products'] as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                continue;
            }

            // $variations = $item['variations'];
            // $productVariationPrice = 0;

            // if ($variations) {
            //     foreach ($variations as $variation) {
            //         if (isset($variation['options']) && is_array($variation['options'])) {
            //             foreach ($variation['options'] as $option) {
            //                 if (!empty($option['selected']) && $option['selected'] === true) {
            //                     $productVariationPrice += $option['price'] ?? 0;
            //                 }
            //             }
            //         }
            //     }
            // }

            // $pricePerUnit = $item['price'] + $productVariationPrice;
            $pricePerUnit = $item['price'];
            $quantity = $item['quantity'];
            // $itemTotal = $pricePerUnit * $quantity;
            // $totalPrice += $itemTotal;

            $orderProducts[] = [
                'product_id' => $item['product_id'],
                'quantity' => $quantity,
                'price' => $pricePerUnit,
                'notes' => $item['notes'] ?? null,
                'variation' => json_encode($item['variations']) ?? null,
            ];
        }

        $discount = $data['discount'] ?? 0;
        $type = $rtableIdf ? 'dine-in' : $data['type'];
        $tableNo = $data['tableNo'] ?? null;
        $finalPrice = $data['total_price'] - $discount;
        $uniqid = uniqid();
        $orderNote = $data['notes'] ?? null;
        $orderStatus = $data['status'] ?? 'pending';
        // $identifier = Identifier::make('Product', $product->id, 4);
        // Identifier::make('Order',);
        $order = Order::create([
            'identifier' => $rtableIdf ?? null,
            'order_number' => 'ORD-' . $uniqid,
            'type' => $type,
            'status' => $orderStatus,
            'notes' => $orderNote,
            'customer_id' => $customer->id,
            'discount' => $discount,
            'invoice_no' => 'INV-' . $uniqid,
            'table_no' => $tableNo,
            'total_price' => $finalPrice,
            'restaurant_id' => $restaurantId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $identifier = Identifier::make('Order', $order->id, 3);
        $invoice_no = Identifier::make('Invoice', $order->id, 3);
        $order->update(['identifier' => $identifier, 'invoice' => $invoice_no]);
        foreach ($orderProducts as $orderProduct) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $orderProduct['product_id'],
                'quantity' => $orderProduct['quantity'],
                'price' => $orderProduct['price'],
                'notes' => $orderProduct['notes'] ?? null,
                'variation' => json_encode($orderProduct['variation']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $order->load('orderProducts.product');

        $notification = $this->createNotification($order);

        $noti = new NotifyResource($notification);

        Helper::sendPusherToUser($noti, 'notification-channel', 'notification-update');

        return ServiceResponse::success('Order created successfully', ['data' => $order]);
    }

    public function trackCustomerOrder($orderNumber)
    {
        $order = Order::with('orderProducts.product', 'customer', 'restaurant', 'table', 'notification')
            ->where('order_number', $orderNumber)->first();
        if (!$order) {
            return ServiceResponse::error("$orderNumber is not found", 404);
        }

        $data = new AddOrderBookingResource($order);
        return ServiceResponse::success("Customer Order Tracked Successfully", ['order' => $data]);
    }
    public function searchCustomerOrder(Request $request)
    {
        $orderNumber = $request->input('order_number');
        $phone = $request->input('phone');




        // dd($orderNumber, $phone);
        $order = Order::with('orderProducts.product', 'customer', 'restaurant', 'table', 'notification')
            ->where('order_number', $orderNumber)
            ->orWhereHas('customer', function ($query) use ($phone) {
                $query->where('phone', $phone);
            })
            ->first();
        if (!$order) {
            return ServiceResponse::error("$orderNumber is not found recheck your input details", 404);
        }

        $data = new AddOrderBookingResource($order);
        return ServiceResponse::success("Customer Order Tracked Successfully", ['order' => $data]);
    }
}
