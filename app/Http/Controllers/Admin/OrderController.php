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
use App\Http\Resources\Admin\DeletedOrderResource;
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
    use NotificationTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);

        $resID = $request->restaurant_id;

        $query = Order::query()
            ->where('restaurant_id', $resID)
            ->with('customer', 'table_no', 'orderProducts', 'table')->with(['orderProducts.productProp'])->orderBy('id', 'desc');

        if ($search) {
            $query->where('order_number', 'like', '%' . $search . '%');
        }

        if ($filters) {
            $filters = json_decode($filters, true);

            if (isset($filters['order_id']) && !empty($filters['order_id'])) {
                $query->where('order_number', 'like', '%' . $filters['order_id'] . '%');
            }

            if (isset($filters['total_price']) && !empty($filters['total_price'])) {
                $query->where('total_price', '<', $filters['total_price'])
                    ->orWhere('total_price', '=', $filters['total_price'])->orderByDesc('total_price');
            }

            if (isset($filters['type']) && !empty($filters['type'])) {
                $query->where('order_type', 'like', '%' . $filters['type'] . '%');
            }

            if (isset($filters['status']) && !empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['customer_name']) && !empty($filters['customer_name'])) {
                $query->whereHas('customer', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['customer_name'] . '%');
                });
            }

            if (isset($filters['phone']) && !empty($filters['phone'])) {
                $query->whereHas('customer', function ($q) use ($filters) {
                    $q->where('phone', 'like', '%' . $filters['phone'] . '%');
                });
            }

            if (isset($filters['created_at']) && !empty($filters['created_at'])) {
                $query->whereDate('created_at', $filters['created_at']);
            }

            if (isset($filters['started_from']) && !empty($filters['started_from'])) {
                $query->whereDate('created_at', '>=', $filters['started_from']);
            }

            if (isset($filters['ended_at']) && !empty($filters['ended_at'])) {
                $query->whereDate('created_at', '<=', $filters['ended_at']);
            }

            if (isset($filters['is_paid']) && $filters['is_paid'] !== '') {
                $query->where('is_paid', $filters['is_paid']);
            }
            if (isset($filters['table']) && !empty($filters['table'])) {
                $query->whereHas('table', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['table'] . '%');
                });
            }
        }

        // Before pagination
        $ordersForTotals = (clone $query)->get();

        $totalTax = $ordersForTotals->sum('tax_amount');
        $totalDiscount = $ordersForTotals->sum('discount_value');


        // Calculate total_final_total with your condition
        $totalFinalTotal = $ordersForTotals->sum(function ($order) {
            // If final_total is 0 or null, use total_price instead
            return ($order->final_total == 0 || $order->final_total === null)
                ? $order->total_price
                : $order->final_total;
        });

        $totalPrice = $ordersForTotals->sum('total_price');

        $query->orderBy('id', 'desc');
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        $data->getCollection()->transform(function ($item) {
            return new OrderResource($item);
        });


        return ServiceResponse::success("Order list successfully", [
            'total_tax' => $totalTax,
            'total_discount' => $totalDiscount,
            'total_final_total' => $totalFinalTotal,
            'total_price' => $totalPrice,

            'data' => $data
        ]);
    }

    public function deletedIndex(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);
        $resID = $request->input('restaurant_id', null);

        $query = Order::onlyTrashed()
            ->with([
                'customer',
                'table_no',
                'table',
                'orderProducts' => function ($q) {
                    $q->withTrashed() // include soft-deleted orderProducts
                        ->with([
                            'product' => function ($q2) {
                                $q2->withTrashed(); // include soft-deleted product
                            },
                            'productProp' => function ($q3) {
                                $q3->withTrashed(); // include soft-deleted productProp
                            }
                        ]);
                }
            ])
            ->orderByDesc('id');
        // ✅ restaurant filter
        if (!empty($resID)) {
            $query->where('restaurant_id', $resID);
        }

        // ✅ search filter
        if (!empty($search)) {
            $query->where('order_number', 'like', '%' . $search . '%');
        }

        // ✅ extra filters
        if (!empty($filters) && $filters !== 'null') {
            $filters = json_decode($filters, true);

            if (is_array($filters)) {
                if (!empty($filters['order_id'])) {
                    $query->where('order_number', 'like', '%' . $filters['order_id'] . '%');
                }

                if (!empty($filters['total_price'])) {
                    $query->where(function ($q) use ($filters) {
                        $q->where('total_price', '<', $filters['total_price'])
                            ->orWhere('total_price', '=', $filters['total_price']);
                    })->orderByDesc('total_price');
                }

                if (!empty($filters['type'])) {
                    $query->where('order_type', 'like', '%' . $filters['type'] . '%');
                }

                if (!empty($filters['status'])) {
                    $query->where('status', $filters['status']);
                }

                if (!empty($filters['customer_name'])) {
                    $query->whereHas('customer', function ($q) use ($filters) {
                        $q->where('name', 'like', '%' . $filters['customer_name'] . '%');
                    });
                }

                if (!empty($filters['phone'])) {
                    $query->whereHas('customer', function ($q) use ($filters) {
                        $q->where('phone', 'like', '%' . $filters['phone'] . '%');
                    });
                }

                if (!empty($filters['created_at'])) {
                    $query->whereDate('created_at', $filters['created_at']);
                }

                if (!empty($filters['started_from'])) {
                    $query->whereDate('created_at', '>=', $filters['started_from']);
                }

                if (!empty($filters['ended_at'])) {
                    $query->whereDate('created_at', '<=', $filters['ended_at']);
                }

                if (isset($filters['is_paid']) && $filters['is_paid'] !== '') {
                    $query->where('is_paid', $filters['is_paid']);
                }

                if (!empty($filters['table'])) {
                    $query->whereHas('table', function ($q) use ($filters) {
                        $q->where('name', 'like', '%' . $filters['table'] . '%');
                    });
                }
            }
        }

        // ✅ clone query for totals
        $ordersForTotals = (clone $query)->get();

        $totalTax = $ordersForTotals->sum('tax_amount');
        $totalDiscount = $ordersForTotals->sum('discount_value');
        $totalFinalTotal = $ordersForTotals->sum(function ($order) {
            return ($order->final_total == 0 || $order->final_total === null)
                ? $order->total_price
                : $order->final_total;
        });
        $totalPrice = $ordersForTotals->sum('total_price');

        // ✅ paginate
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        $data->getCollection()->transform(function ($item) {
            return new DeletedOrderResource($item);
        });

        return ServiceResponse::success("Deleted orders list successfully", [
            'total_tax' => $totalTax,
            'total_discount' => $totalDiscount,
            'total_final_total' => $totalFinalTotal,
            'total_price' => $totalPrice,
            'data' => $data
        ]);
    }




    public function totals(Request $request)
    {

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);

        $resID = $request->restaurant_id;

        $query = Order::query()
            ->where('restaurant_id', $resID)
            ->with('customer', 'table_no', 'orderProducts', 'table')->with(['orderProducts.productProp'])->orderBy('id', 'desc');

        if ($search) {
            $query->where('order_number', 'like', '%' . $search . '%');
        }

        if ($filters) {
            $filters = json_decode($filters, true);

            if (isset($filters['order_id']) && !empty($filters['order_id'])) {
                $query->where('order_number', 'like', '%' . $filters['order_id'] . '%');
            }

            if (isset($filters['total_price']) && !empty($filters['total_price'])) {
                $query->where('total_price', '<', $filters['total_price'])
                    ->orWhere('total_price', '=', $filters['total_price'])->orderByDesc('total_price');
            }

            if (isset($filters['type']) && !empty($filters['type'])) {
                $query->where('type', 'like', '%' . $filters['type'] . '%');
            }

            if (isset($filters['status']) && !empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['customer_name']) && !empty($filters['customer_name'])) {
                $query->whereHas('customer', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['customer_name'] . '%');
                });
            }

            if (isset($filters['phone']) && !empty($filters['phone'])) {
                $query->whereHas('customer', function ($q) use ($filters) {
                    $q->where('phone', 'like', '%' . $filters['phone'] . '%');
                });
            }

            if (isset($filters['created_at']) && !empty($filters['created_at'])) {
                $query->whereDate('created_at', $filters['created_at']);
            }

            if (isset($filters['started_from']) && !empty($filters['started_from'])) {
                $query->whereDate('created_at', '>=', $filters['started_from']);
            }

            if (isset($filters['ended_at']) && !empty($filters['ended_at'])) {
                $query->whereDate('created_at', '<=', $filters['ended_at']);
            }

            if (isset($filters['is_paid']) && $filters['is_paid'] !== '') {
                $query->where('is_paid', $filters['is_paid']);
            }
            if (isset($filters['table']) && !empty($filters['table'])) {
                $query->whereHas('table', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['table'] . '%');
                });
            }
        }

        // Before pagination
        $ordersForTotals = (clone $query)->get();

        $totalTax = $ordersForTotals->sum('tax_amount');
        $totalDiscount = $ordersForTotals->sum('discount_value');

        // Calculate total_final_total with your condition
        $totalFinalTotal = $ordersForTotals->sum(function ($order) {
            // If final_total is 0 or null, use total_price instead
            return ($order->final_total == 0 || $order->final_total === null)
                ? $order->total_price
                : $order->final_total;
        });

        $totalPrice = $ordersForTotals->sum('total_price');

        $query->orderBy('id', 'desc');
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        $data->getCollection()->transform(function ($item) {
            return new OrderResource($item);
        });
        return ServiceResponse::success("Order list successfully", [
            'total_tax' => $totalTax,
            'total_discount' => $totalDiscount,
            'total_final_total' => $totalFinalTotal,
            'total_price' => $totalPrice,
        ]);
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
        logger()->info('Products data:', $data['products']);

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
            $category = $item['category'] ?? null;
            $quantity = $item['quantity'];
            $itemTotal = $pricePerUnit * $quantity;

            $totalPrice += $itemTotal;

            $orderProducts[] = [
                'category' => $category,
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

        if ($table) {
            $existingBooking = RTablesBooking::where('rtable_id', $table->id)
                ->where(function ($query) use ($data) {
                    $query->whereBetween('booking_start', [now(), now()->addHour()])
                        ->orWhereBetween('booking_end', [now(), now()->addHour()])
                        ->orWhere(function ($q) {
                            $q->where('booking_start', '<=', now())
                                ->where('booking_end', '>=', now()->addHour());
                        });
                })
                ->first();

            if ($existingBooking) {
                return response()->json(
                    ServiceResponse::error("The table is already booked for the given time period."),
                    400 // optional: explicitly send HTTP status code
                );
            }
        }

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
        $taxPercentage = $request->tax_percentage ?? 0;

        $taxAmount = $request->tax_amount ?? 0;
        $tipsAmount = $request->tips_amount ?? 0;
        $tips = $request->tips ?? 0;
        $deliveryCharges = $request->delivery_charges ?? 0;

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
            'tax_percentage' => $taxPercentage, // Store tax percentage
            'tax_amount' => $taxAmount,
            'tips' => $tips,
            'source' => $request->is_from_pos ? 'pos' : 'website',
            'tips_amount' => $tipsAmount,
            'delivery_charges' => $deliveryCharges,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $identifier = Identifier::make('Order', $order->id, 3);
        $invoice_no = Identifier::make('Invoice', $order->id, 3);
        // $order->update(['identifier' => $identifier, 'invoice_no' => $invoice_no]);
        $order->update(
            [
                'identifier' => Identifier::make('Order', $order->id, 3),
                'invoice_no' => Identifier::make('Invoice', $order->id, 3) . '-' . $order->order_number . '-' . $order->id . '-' . $order->created_at->format('Ymd')
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
                'category' => $orderProduct['category'],
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
                'rtable_id' => $table->id,
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
        $order = Order::withTrashed() // include soft-deleted orders
            ->with([
                'customer',
                'table_no',
                'table',
                'restaurant',
                'orderProducts' => function ($q) {
                    $q->withTrashed() // include soft-deleted orderProducts
                        ->with([
                            'product' => function ($q2) {
                                $q2->withTrashed(); // include soft-deleted products
                            },
                            'productProp' => function ($q3) {
                                $q3->withTrashed(); // include soft-deleted productProps
                            }
                        ]);
                }
            ])
            ->find($id); // or ->where('id', $id)->first();

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

        $customerName = array_key_exists('customer_name', $data) ? $data['customer_name'] : ($order->customer->name ?? 'Walk-in Customer');
        $customerPhone = array_key_exists('customer_phone', $data) ? $data['customer_phone'] : ($order->customer->phone ?? 'XXXX');

        // Check if name or phone changed
        $nameChanged = $customerName !== ($order->customer->name ?? '');
        $phoneChanged = $customerPhone !== ($order->customer->phone ?? '');

        // If either changed, always create a new user
        if ($nameChanged || $phoneChanged) {
            $user = User::create([
                'name' => $customerName,
                'phone' => $customerPhone,
                'email' => $customerPhone . "@phone.test",
            ]);
        } else {
            // No change, update current user info if needed
            $user = $order->customer;
            $user->update([
                'name' => $customerName,
                'phone' => $customerPhone,
            ]);
        }

        $totalPrice = 0;
        $orderProducts = [];
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
                'notes' => array_key_exists('notes', $item) ? $item['notes'] : null,
                'variation' => array_key_exists('variation', $item) ? json_encode($item['variation']) : null,
            ];
        }

        $discount = array_key_exists('discount', $data) ? $data['discount'] : ($order->discount ?? 0);
        $type = array_key_exists('type', $data) ? $data['type'] : $order->type;
        $table = array_key_exists('table_id', $data) ? Rtable::where('id', $data['table_id'])->first() : null;
        $tableNo = $table ? $table->id : $order->table_no;

        // Table booking logic
        if ($table) {
            $existingBooking = RTablesBooking::where('rtable_id', $table->id)
                ->where('order_id', '!=', $order->id)
                ->where(function ($query) {
                    $query->whereBetween('booking_start', [now(), now()->addHour()])
                        ->orWhereBetween('booking_end', [now(), now()->addHour()])
                        ->orWhere(function ($q) {
                            $q->where('booking_start', '<=', now())
                                ->where('booking_end', '>=', now()->addHour());
                        });
                })
                ->first();

            if ($existingBooking) {
                return response()->json(
                    ServiceResponse::error("The table is already booked for the given time period."),
                    400
                );
            }
        }

        $finalPrice = $totalPrice - ($discount ?? 0);
        $orderNote = array_key_exists('notes', $data) ? $data['notes'] : $order->notes;
        $orderStatus = array_key_exists('status', $data) ? $data['status'] : $order->status;
        $paymentMethod = array_key_exists('payment_method', $data) ? $data['payment_method'] : $order->payment_method;
        $orderType = array_key_exists('order_type', $data) ? $data['order_type'] : $order->order_type;
        $deliveryAddress = array_key_exists('delivery_address', $data) ? $data['delivery_address'] : $order->delivery_address;
        $resID = array_key_exists('restaurant_id', $data) ? $data['restaurant_id'] : $order->restaurant_id;
        $couponCode = array_key_exists('coupon_code', $data) ? $data['coupon_code'] : $order->coupon_code;
        $discountValue = array_key_exists('discount_value', $data) ? $data['discount_value'] : $order->discount_value;
        $finalTotal = array_key_exists('final_total', $data) ? $data['final_total'] : $order->final_total;
        $taxPercentage = array_key_exists('tax_percentage', $data) ? $data['tax_percentage'] : $order->tax_percentage;
        $tips = array_key_exists('tips', $data) ? $data['tips'] : $order->tips;
        $tipsAmount = array_key_exists('tips_amount', $data) ? $data['tips_amount'] : $order->tips_amount;
        $deliveryCharges = array_key_exists('delivery', $data) ? $data['delivery_charges'] : $order->delivery_charges;
        $taxAmount = array_key_exists('tax_amount', $data) ? $data['tax_amount'] : $order->tax_amount;


        $order->update([
            'restaurant_id' => $resID,
            'type' => $type,
            'status' => $orderStatus,
            'notes' => $orderNote,
            'customer_id' => $user->id ?? null,
            'discount' => $discount,
            'table_no' => $tableNo,
            'total_price' => $finalPrice,
            'payment_method' => $paymentMethod,
            'order_type' => $orderType,
            'delivery_address' => $deliveryAddress,
            'coupon_code' => $couponCode,
            'discount_value' => $discountValue,
            'final_total' => $finalTotal,
            'source' => $request->is_from_pos ? 'pos' : 'website',
            'tax_percentage' => $taxPercentage, // Update tax percentage
            'tax_amount' => $taxAmount,
            'tips' => $tips,
            'tips_amount' => $tipsAmount,
            'delivery_charges' => $deliveryCharges,
            // Do NOT set 'updated_at' here, let Eloquent handle it
        ]);

        // Sync order products
        $existingProducts = $order->orderProducts->keyBy('product_id');
        $newProductIds = [];
        foreach ($orderProducts as $orderProduct) {
            $newProductIds[] = $orderProduct['product_id'];
            if ($existingProducts->has($orderProduct['product_id'])) {
                $existingProducts[$orderProduct['product_id']]->update($orderProduct);
            } else {
                $order->orderProducts()->create($orderProduct);
            }
        }
        // Remove products not in the new list
        $order->orderProducts()->whereNotIn('product_id', $newProductIds)->delete();

        // Update invoice if exists
        $invoice = Invoice::where('order_id', $order->id)->first();
        if ($invoice) {
            $invoice->update([
                'payment_method' => $paymentMethod,
                'total' => $order->total_price,
                'status' => $invoice->status ?? 'pending',
                'notes' => $invoice->notes ?? "",
            ]);
        }

        // Table booking update (optional)
        if ($table) {
            $booking = RTablesBooking::where('order_id', $order->id)->first();
            if ($booking) {
                $booking->update([
                    'rtable_id' => $table->id,
                    'customer_id' => $user->id ?? null,
                    'restaurant_id' => $resID,
                    'order_number' => $order->order_number,
                    'payment_method' => $order->payment_method,
                    'booking_start' => now(),
                    'booking_end' => now()->addHour(),
                    'no_of_seats' => array_key_exists('no_of_seats', $data) ? $data['no_of_seats'] : 2,
                    'description' => array_key_exists('description', $data) ? $data['description'] : null,
                    'status' => 'reserved',
                ]);
            } else {
                $booking = RTablesBooking::create([
                    'rtable_id' => $table->id,
                    'customer_id' => $user->id ?? null,
                    'restaurant_id' => $resID,
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_method' => $order->payment_method,
                    'booking_start' => now(),
                    'booking_end' => now()->addHour(),
                    'no_of_seats' => array_key_exists('no_of_seats', $data) ? $data['no_of_seats'] : 2,
                    'description' => array_key_exists('description', $data) ? $data['description'] : null,
                    'status' => 'reserved',
                ]);
            }
            RTableBooking_RTable::updateOrCreate(
                [
                    'rtable_booking_id' => $booking->id,
                    'rtable_id' => $table->id,
                ],
                [
                    'restaurant_id' => $resID,
                    'booking_start' => $booking->booking_start,
                    'booking_end' => $booking->booking_end,
                    'no_of_seats' => array_key_exists('no_of_seats', $data) ? $data['no_of_seats'] : null,
                ]
            );
        }

        // Reload the updated order with its products
        $order->load('orderProducts.product');

        // Notification
        $notification = $this->createNotification($order);
        $noti = new NotifyResource($notification);
        Helper::sendPusherToUser($noti, 'notification-channel', 'notification-update');

        // Send email to the customer
        Helper::sendEmail($user->email, 'Your Order Details', 'emails.order_details', ['order' => $order]);

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

    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'is_paid' => 'required|boolean',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return ServiceResponse::error('Order not found');
        }

        $order->update([
            'is_paid' => $request->is_paid,
        ]);

        // Optionally, send notification or perform other actions here

        return ServiceResponse::success('Order payment status updated successfully', $order);
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|exists:restaurant_timings_meta,id',
        ]);

        if ($validator->fails()) {
            return ServiceResponse::error('Validation failed', $validator->errors());
        }

        $ids = $request->input('ids', []);
        Order::whereIn('id', $ids)->delete();

        return ServiceResponse::success("Bulk delete successful", ['ids' => $ids]);
    }
    public function restore($id)
    {
        try {
            $order = Order::onlyTrashed()->findOrFail($id);

            $order->restore();

            return ServiceResponse::success("Order restored successfully", [
                'order' => new OrderResource($order)
            ]);
        } catch (\Exception $e) {
            return ServiceResponse::error("Failed to restore order: " . $e->getMessage());
        }
    }
    public function restoreMultiple(Request $request)
    {
        $ids = $request->input('ids', []); // pass an array of order IDs

        if (empty($ids)) {
            return ServiceResponse::error("No orders selected for restore");
        }

        $restored = Order::onlyTrashed()->whereIn('id', $ids)->restore();

        return ServiceResponse::success("Orders restored successfully", [
            'restored_count' => $restored
        ]);
    }
    // Force delete a single order
    public function forceDelete($id)
    {
        try {
            $order = Order::onlyTrashed()->findOrFail($id);

            $order->forceDelete();

            return ServiceResponse::success("Order permanently deleted", [
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return ServiceResponse::error("Failed to force delete order: " . $e->getMessage());
        }
    }

    // Force delete multiple orders
    public function forceDeleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []); // expects array of IDs

        if (empty($ids)) {
            return ServiceResponse::error("No orders selected for permanent deletion");
        }

        $deleted = Order::onlyTrashed()->whereIn('id', $ids)->forceDelete();

        return ServiceResponse::success("Orders permanently deleted", [
            'deleted_count' => $deleted
        ]);
    }


}
