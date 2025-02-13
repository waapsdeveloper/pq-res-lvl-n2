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

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);

        $category = $request->input('category_id', '');
        $active_restaurant = Helper::getActiveRestaurantId();
        $resID = $request->restaurant_id == -1 ? $active_restaurant->id : $request->restaurant_id;

        $query = Order::query()
            ->where('restaurant_id', $resID)
            ->with('customer', 'table_no', 'orderProducts', 'table')->with(['orderProducts.productProp'])->orderBy('id', 'desc');


        // Optionally apply search filter if needed
        if ($search) {
            $query->where('order_number', 'like', '%' . $search . '%');
        }
        if ($filters) {
            $filters = json_decode($filters, true); // Decode JSON string into an associative array
            if (isset($filters['order_id']) && !empty($filters['order_id'])) {
                $query->where('order_number', 'like', '%' . $filters['order_id'] . '%');
            }

            if (isset($filters['total_price']) && !empty($filters['total_price'])) {
                $query->where('total_price', '<',  $filters['total_price'])
                    ->orWhere('total_price', '=',  $filters['total_price'])->orderByDesc('total_price');

                // dd($filters['total_price']);
            }
            if (isset($filters['type']) && !empty($filters['type'])) {
                $query->where('type', 'like', '%' . $filters['type'] . '%');
            }

            if (isset($filters['status']) && !empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (isset($filters['Customer_name']) && !empty($filters['Customer_name'])) {
                $query->whereHas('customer', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['Customer_name'] . '%');
                });
            }
            if (isset($filters['phone']) && !empty($filters['phone'])) {
                $query->whereHas('customer', function ($q) use ($filters) {
                    $q->where('phone', 'like', '%' . $filters['phone'] . '%');
                });
            }
            // if (isset($filters['table']) && !empty($filters['table'])) {
            //     $query->whereHas('table_no', function ($q) use ($filters) {
            //         $q->where('name', 'like', '%' . $filters['table'] . '%');
            //     });
            // }
        }

        // Paginate the results
        $query->orderBy('id', 'desc');
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new OrderResource($item);
        });

        // Return the response with image URLs included
        return ServiceResponse::success("Order list successfully", ['data' => $data]);
    }

    public function makeOrderBookings(MakeOrderBooking $request)
    {
        $data = $request->validated();
        $phone = $data['phone'];

        // create or update custoer by phone 
        $customer = auth()->user();

        // $customer = $this->getCustomerByPhone($phone);

        $rtableIdf = $request->input('table_identifier', null);

        if (!empty($rtableIdf)) {
            $restaurantId = $this->tableIdentifier($rtableIdf);
        } else {
            $restaurantId = (int) $request->restaurant_id;
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
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(str()->random(6));

        $order = Order::create([
            'identifier' => $rtableIdf ?? null,
            'order_number' => $orderNumber,
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
                'variation' => $orderProduct['variation'],
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

        $order = Order::with('orderProducts.product', 'customer', 'restaurant', 'table', 'notification')
            ->where('order_number', $orderNumber)
            ->orWhereHas('customer', function ($query) use ($phone) {
                $query->where('phone', $phone);
            })
            ->first();
        if (!$order) {
            return ServiceResponse::error("$orderNumber is not found .Recheck your input details", 404);
        }

        $data = new AddOrderBookingResource($order);
        return ServiceResponse::success("Customer Order Tracked Successfully", ['order' => $data]);
    }
}
