<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\Identifier;
use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\StoreOrder;
use App\Http\Requests\Admin\Order\UpdateOrder;
use App\Http\Requests\Admin\Order\UpdateOrderStatus;
use App\Http\Resources\Admin\NotifyResource;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Payments;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Http\Controllers\Admin\RTableBookingController;
use App\Http\Requests\Admin\RTablebooking\StoreRTablesBooking;
use App\Models\Rtable;
use App\Models\RTableBooking_RTable;
use App\Models\RTablesBooking;
use Illuminate\Support\Facades\App;

class OrderController extends Controller
{
    use  NotificationTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);

        // $category = $request->input('category_id', '');
        // $active_restaurant = Helper::getActiveRestaurantId();
        $resID = $request->restaurant_id; // == -1 ? $active_restaurant->id : $request->restaurant_id;

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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrder $request)
    {
        // $data = $request->all();

        $data = $request->validated();
        $resID = $request->restaurant_id;

        $customerName = $data['customer_name'] ?? 'Walk-in Customer';
        $customerPhone = $data['customer_phone'] ?? 'XXXX';

        $user = User::where('phone', $customerPhone)->first();

        if (!$user) {
            $user = User::create([
                'name' => $customerName,
                'phone' => $customerPhone,
                'email' => $customerPhone . "@phone.test",
            ]);
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
                'variation' => $item['variation'] ?? null,
            ];
        }

        $discount = $data['discount'] ?? 0;
        $type = $data['type'] ?? null;


        $table = $request->has('table_id') ? Rtable::where('id', $data['table_id'])->first() : null;
        $tableNo = $table ? $table->id : null;

        // $finalPrice = $totalPrice - ($totalPrice * ($discount / 100));
        $finalPrice = $totalPrice - $discount;
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(str()->random(6));
        $orderNote = $request->notes;
        $orderStatus = $request->status;

        $paymentMethod = $request->payment_method;
        $orderType = $request->order_type;
        $deliveryAddress = $request->delivery_address;
        $resID = $request->restaurant_id;
        $couponCode = $request->coupon_code;
        $discountValue = $request->discount_value;
        $finalTotal = $request->final_total;


        $order = Order::create([
            'identifier' => 'ORD-',
            'restaurant_id' => $resID,
            'order_number' => $orderNumber,
            'type' => $type,
            'status' => $orderStatus,
            'notes' => $orderNote,
            'customer_id' => $user->id ?? null,
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
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $identifier = Identifier::make('Order', $order->id, 3);
        $invoice_no = Identifier::make('Invoice', $order->id, 3);
        // $order->update(['identifier' => $identifier, 'invoice_no' => $invoice_no]);
        $order->update(
            [
                'identifier' => Identifier::make('Order', $order->id, 3),
                'invoice_no' => Identifier::make('Invoice', $order->id, 3)
            ]
        );


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

        $data = new OrderResource($order);


        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_no' => $order->invoice_no,
            'invoice_date' => now(),
            'restaurant_id' => $resID,
            'payment_method' => $paymentMethod,
            'total' => $order->total_price,
            'status' => 'pending',
            'notes' => ""
        ]);

        logger()->info('Invoice created successfully', ['invoice_id' => $invoice->id]);

        // If table_id is not null, update the table status to occupied
        if ($table) {

            // Save the main booking
            $booking = RTablesBooking::create([
                'customer_id' => $user->id ?? null,
                'restaurant_id' => $resID,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_method' => $order->payment_method,
                'booking_start' => now(),
                'booking_end' => now()->addHour(),
                'no_of_seats' => $data['no_of_seats'] ?? 2,
                'description' => $data['description'] ?? null,
                'status' => 'reserved',
            ]);

            RTableBooking_RTable::create([
                'restaurant_id' => $resID,
                'rtable_booking_id' => $booking->id,
                'rtable_id' => $table->id,
                'booking_start' => $booking->booking_start,
                'booking_end' => $booking->booking_end,
                'no_of_seats' => $data['no_of_seats'],
            ]);
        }

        // Send email to the customer
        Helper::sendEmail($user->email, 'Your Order Details', 'emails.order_details', ['order' => $order]);

        return ServiceResponse::success("Order list successfully", ['data' => $data]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::where('id', $id)
            ->with('orderProducts.product', 'restaurant')
            ->with('customer', 'table_no', 'table')->with(['orderProducts.productProp'])
            ->first();

        if (!$order) {
            return ServiceResponse::error('Order not found');
        }

        $data = new OrderResource($order);

        return ServiceResponse::success('Order details fetched successfully', [
            'order' => $data,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrder $request, $id)
    {
        $data = $request->validated();

        $order = Order::find($id);
        if (!$order) {
            return ServiceResponse::error("Order with ID $id not found.");
        }

        $totalPrice = 0;
        $orderProducts = [];

        // Process products
        foreach ($data['products'] as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                return ServiceResponse::error("Product with ID {$item['product_id']} not found.");
            }

            $pricePerUnit = $item['price'];
            $quantity = $item['quantity'];
            $itemTotal = $pricePerUnit * $quantity;

            $totalPrice += $itemTotal;

            $orderProducts[] = [
                'product_id' => $item['product_id'],
                'quantity' => $quantity,
                'price' => $pricePerUnit,
                'notes' => $item['notes'] ?? null,
                'variation' => $item['variation'] ?? null,
            ];
        }

        // Calculate discount and final price
        $discount = $data['discount'] ?? $order->discount;
        $finalPrice = $totalPrice - $discount;

        // Update order details
        $order->update([
            'discount' => $discount,
            'total_price' => $finalPrice,
            'status' => $data['status'] ?? $order->status,
            'notes' => $data['notes'] ?? $order->notes,
            'type' => $data['type'] ?? $order->type,
            'table_no' => $data['tableNo'] ?? $order->table_no,
            'updated_at' => now(),
        ]);

        // Synchronize order products
        $existingProducts = $order->orderProducts->keyBy('product_id');
        foreach ($orderProducts as $orderProduct) {
            if ($existingProducts->has($orderProduct['product_id'])) {
                // Update existing product
                $existingProducts[$orderProduct['product_id']]->update($orderProduct);
            } else {
                // Add new product
                $order->orderProducts()->create($orderProduct);
            }
        }

        // Remove products that are not in the updated list
        $newProductIds = collect($orderProducts)->pluck('product_id');
        $order->orderProducts()
            ->whereNotIn('product_id', $newProductIds)
            ->delete();

        // Reload the updated order with its products
        $order->load('orderProducts.product');

        $data = new OrderResource($order);

        return ServiceResponse::success("Order updated successfully", ['data' => $data]);
    }


    public function destroy(string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return ServiceResponse::error('Order not found');
        }
        // return response()->json($id);
        $orderProducts = OrderProduct::where('order_id', $order->id)->delete();
        $order->delete();
        return ServiceResponse::success('Order deleted successfully', $order);
    }

    public function updateStatus(UpdateOrderStatus $request, $id)
    {
        $data = $request->validated();


        $order = Order::find($id);

        if (!$order) {
            return ServiceResponse::error('Order not found');
        }

        $order->update([
            'status' => $data['status'],
        ]);

        $notification = $this->createNotification($order);

        $noti = new NotifyResource($notification);
        Helper::sendPusherToUser($noti, 'notification-channel', 'notification-update-' . $order->order_number);
        return ServiceResponse::success('Order status updated successfully', $order);
    }
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|exists:restaurant_timings,id',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $ids = $request->input('ids', []);
        Order::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }
}
