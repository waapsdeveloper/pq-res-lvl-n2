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
use App\Traits\Traits\Frontend\CustomerTrait;
use App\Traits\Traits\Frontend\TableBookingTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    use CustomerTrait;
    use TableBookingTrait;

    public function makeOrderBookings(Request $request)
    {
        $data = $request->validated();

        $phone = $data['phone'];
        $customer = $this->getCustomerByPhone($phone);

        $rtableIdf = $request->input('table_identifier', null);
        $restaurant = $this->tableIdentifier($rtableIdf);
        $totalPrice = 0;
        $orderProducts = [];

        foreach ($data['products'] as $item) {

            $product = Product::find($item['id']);
            if (!$product) {
                continue;
            }

            $variations = $item['variations'];
            $productVariationPrice = 0;

            if ($variations) {
                foreach ($variations as $variation) {
                    if (isset($variation['options']) && is_array($variation['options'])) {
                        foreach ($variation['options'] as $option) {
                            if (!empty($option['selected']) && $option['selected'] === true) {
                                $productVariationPrice += $option['price'] ?? 0;
                            }
                        }
                    }
                }
            }

            $pricePerUnit = $item['price'] + $productVariationPrice;
            $quantity = $item['quantity'];
            $itemTotal = $pricePerUnit * $quantity;
            $totalPrice += $itemTotal;

            $orderProducts[] = [
                'product_id' => $item['id'],
                'quantity' => $quantity,
                'price' => $pricePerUnit,
                'notes' => $item['notes'] ?? null,
                'variation' => json_encode($item['variations']) ?? null,
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
            'customer_id' => $customer->id,
            'discount' => $discount,
            'invoice_no' => 'INV-' . $uniqid,
            'table_no' => $tableNo,
            'total_price' => $finalPrice,
            'restaurant_id' => $restaurant->id ?? 1,
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
        return ServiceResponse::success('Order created successfully', ['data' => $order]);
    }

    public function trackCustomerOrder($orderNumber)
    {
        $order = Order::with('orderProducts.product', 'customer', 'restaurant', 'table')
            ->where('order_number', $orderNumber)->first();
        $data = new AddOrderBookingResource($order);
        return ServiceResponse::success("Customer Order Tracked Successfully", ['order' => $data]);
    }
}
