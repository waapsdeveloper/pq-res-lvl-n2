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
use App\Models\OrderLog;
use App\Models\RTablesBooking;
use Illuminate\Support\Facades\App;
use Log;

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

            // Filter by Order ID
            if (!empty($filters['order_id'])) {
                $query->where('order_number', 'like', '%' . $filters['order_id'] . '%');
            }

            // Filter by Total Price
            if (!empty($filters['total_price'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('total_price', '<', $filters['total_price'])
                        ->orWhere('total_price', '=', $filters['total_price']);
                })->orderByDesc('total_price');
            }

            // Filter by Order Type (can be array)
            if (!empty($filters['type'])) {
                $types = is_array($filters['type']) ? $filters['type'] : [$filters['type']];
                $query->whereIn('order_type', $types);
            }

            // Filter by Status (can be array)
            if (!empty($filters['status'])) {
                $statuses = is_array($filters['status']) ? $filters['status'] : [$filters['status']];
                $query->whereIn('status', $statuses);
            }

            // Filter by date range
            if (!empty($filters['date_range'])) {
                if (!empty($filters['date_range']['startDate'])) {
                    $query->whereDate('created_at', '>=', Carbon::parse($filters['date_range']['startDate'])->startOfDay());
                }
                if (!empty($filters['date_range']['endDate'])) {
                    $query->whereDate('created_at', '<=', Carbon::parse($filters['date_range']['endDate'])->endOfDay());
                }
            }

            // Filter by Payment Method (can be array)
            if (!empty($filters['payment_method'])) {
                $methods = is_array($filters['payment_method']) ? $filters['payment_method'] : [$filters['payment_method']];
                $query->whereIn('payment_method', $methods);
            }

            // Filter by Customer Name
            if (!empty($filters['customer_name'])) {
                $query->whereHas('customer', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['customer_name'] . '%');
                });
            }

            // Filter by Customer Phone
            if (!empty($filters['phone'])) {
                $query->whereHas('customer', function ($q) use ($filters) {
                    $q->where('phone', 'like', '%' . $filters['phone'] . '%');
                });
            }

            // Filter by Table Name
            if (!empty($filters['table'])) {
                $query->whereHas('table', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['table'] . '%');
                });
            }

            // Date Filters
            if (!empty($filters['created_at'])) {
                $query->whereDate('created_at', $filters['created_at']);
            }

            if (!empty($filters['started_from'])) {
                $query->whereDate('created_at', '>=', $filters['started_from']);
            }

            if (!empty($filters['ended_at'])) {
                $query->whereDate('created_at', '<=', $filters['ended_at']);
            }

            // Filter by Paid/Unpaid
            if (isset($filters['is_paid']) && $filters['is_paid'] !== '') {
                $query->where('is_paid', $filters['is_paid']);
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
        $performer = auth()->user();
        logger()->info('Performer info', ['performer' => $performer]);

        // --- build product payload helper (include quantity and full variation + selected parts) ---
        $buildProductPayload = function ($op) {
            // $op is OrderProduct model
            $productId = $op->product_id ?? null;
            $prodModel = $op->product ?? ($productId ? Product::find($productId) : null);
            $productName = $prodModel ? $prodModel->name : null;
            $quantity = $op->quantity ?? null;
            $price = $op->price ?? null;
            $notes = $op->notes ?? null;

            $variationRaw = $op->variation ?? null;
            // decode variation if JSON string
            if (is_string($variationRaw) && $variationRaw !== '') {
                $decoded = json_decode($variationRaw, true);
                $variation = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $variationRaw;
            } else {
                $variation = $variationRaw;
            }

            // extract selected options (if structure contains selectedOption or selected flag)
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
                'variation' => $variation,                 // full decoded variation payload
                'selected_variations' => $selectedVariations, // only selected parts
            ];
        };

        // Build array of product payloads for this order
        $productPayloads = [];
        foreach ($order->orderProducts as $op) {
            $productPayloads[] = $buildProductPayload($op);
        }

        // Log order_created with product assignment (new_value = full products array)
        OrderLog::create([
            'order_id' => $order->id,
            'event_type' => 'order_created',
            'old_value' => null,
            'new_value' => json_encode($productPayloads, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'performed_by' => $performer ? $performer->name : 'System',
            'performed_by_id' => $performer ? $performer->id : null,
        ]);

        // Keep initial status log (separate)
        OrderLog::create([
            'order_id'        => $order->id,
            'event_type'      => 'status_change',
            'old_value'       => null,
            'new_value'       => $order->status,
            'performed_by'    => $performer ? $performer->name : 'System',
            'performed_by_id' => $performer ? $performer->id : null,
        ]);

        // Keep payment status log (separate)
        OrderLog::create([
            'order_id'        => $order->id,
            'event_type'      => 'payment_status',
            'old_value'       => null,
            'new_value'       => $order->is_paid ? 'Paid' : 'Unpaid',
            'performed_by'    => $performer ? $performer->name : 'System',
            'performed_by_id' => $performer ? $performer->id : null,
        ]);


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


        // Before updating the order, capture old values
        $oldOrderType      = $order->order_type;
        $oldPaymentMethod  = $order->payment_method;
        $existingProducts  = $order->orderProducts->keyBy('product_id');

        // helper: recursively sort arrays for stable JSON comparison
        $sortRecursive = function (&$v) use (&$sortRecursive) {
            if (!is_array($v)) return;
            // sort associative arrays by key, keep numeric arrays order
            if (array_values($v) === $v) {
                foreach ($v as &$item) {
                    $sortRecursive($item);
                }
            } else {
                ksort($v);
                foreach ($v as &$item) {
                    $sortRecursive($item);
                }
            }
        };

        // normalize variation / any JSON value to stable JSON string
        $normalize = function ($val) use ($sortRecursive) {
            if (is_string($val)) {
                $decoded = json_decode($val, true);
                if ($decoded !== null) $val = $decoded;
            }
            if (is_array($val)) {
                $sortRecursive($val);
                return json_encode($val, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
            return json_encode($val, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        };

        // Build new products list from request (you already build $orderProducts earlier)
        $newProductsById = collect($orderProducts)->keyBy('product_id');

        // Compare products: added / removed / updated
        $added   = [];
        $removed = [];
        $updated = [];

        $newIds = $newProductsById->keys()->all();
        $oldIds = $existingProducts->keys()->all();

        // removed
        foreach ($existingProducts as $pid => $oldP) {
            if (!in_array($pid, $newIds)) {
                $removed[] = [
                    'product_id' => $pid,
                    'quantity'   => $oldP->quantity,
                    'price'      => $oldP->price,
                    'notes'      => $oldP->notes,
                    'variation'  => $normalize($oldP->variation),
                ];
            }
        }

        // added & updated
        foreach ($newProductsById as $pid => $newP) {
            if (!isset($existingProducts[$pid])) {
                // added
                $added[] = $newP;
            } else {
                $oldP = $existingProducts[$pid];
                $oldVar = $normalize($oldP->variation);
                $newVar = $normalize($newP['variation'] ?? $newP['variation']);
                // compare key fields
                $changed = false;
                $oldSnapshot = [
                    'product_id' => $pid,
                    'quantity'   => $oldP->quantity,
                    'price'      => (string)$oldP->price,
                    'notes'      => $oldP->notes,
                    'variation'  => $oldVar,
                ];
                $newSnapshot = [
                    'product_id' => $pid,
                    'quantity'   => $newP['quantity'],
                    'price'      => (string)$newP['price'],
                    'notes'      => $newP['notes'] ?? null,
                    'variation'  => $newVar,
                ];
                if (
                    (string)$oldP->quantity !== (string)$newP['quantity'] ||
                    (string)$oldP->price !== (string)$newP['price'] ||
                    ($oldP->notes ?? null) !== ($newP['notes'] ?? null) ||
                    $oldVar !== $newVar
                ) {
                    $updated[] = [
                        'old' => $oldSnapshot,
                        'new' => $newSnapshot,
                    ];
                }
            }
        }

        // Proceed with normal order update & product sync (your existing logic)
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
            'tax_percentage' => $taxPercentage,
            'tax_amount' => $taxAmount,
            'tips' => $tips,
            'tips_amount' => $tipsAmount,
            'delivery_charges' => $deliveryCharges,
        ]);

        // Sync order products (existing code)
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
        $order->orderProducts()->whereNotIn('product_id', $newProductIds)->delete();

        // After update, create OrderLog entries for each detected change
        $performer = auth()->user();
        $performedBy = $performer ? $performer->name : 'System';
        $performedById = $performer ? $performer->id : null;

        // helper: build payload including product name and ONLY the selected variation(s)
        $buildSelectedPayload = function ($item) {
            // $item can be an array snapshot or an Eloquent model/object
            $productId = is_array($item) ? ($item['product_id'] ?? null) : ($item->product_id ?? null);
            $quantity  = is_array($item) ? ($item['quantity'] ?? null) : ($item->quantity ?? null);
            $price     = is_array($item) ? ($item['price'] ?? null) : ($item->price ?? null);
            $notes     = is_array($item) ? ($item['notes'] ?? null) : ($item->notes ?? null);
            $variationRaw = is_array($item) ? ($item['variation'] ?? null) : ($item->variation ?? null);

            // decode if json string
            if (is_string($variationRaw) && $variationRaw !== '') {
                $decoded = json_decode($variationRaw, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $variation = $decoded;
                } else {
                    $variation = null;
                }
            } elseif (is_array($variationRaw)) {
                $variation = $variationRaw;
            } else {
                $variation = null;
            }

            $selectedVariations = [];
            if (is_array($variation)) {
                foreach ($variation as $group) {
                    // prefer explicit selectedOption
                    if (isset($group['selectedOption']) && $group['selectedOption']) {
                        $selectedVariations[] = [
                            'type' => $group['type'] ?? null,
                            'selected' => $group['selectedOption'],
                        ];
                        continue;
                    }

                    // otherwise pick options marked selected
                    if (isset($group['options']) && is_array($group['options'])) {
                        $selectedOptions = [];
                        foreach ($group['options'] as $opt) {
                            if ((isset($opt['selected']) && $opt['selected']) || (isset($opt['is_selected']) && $opt['is_selected'])) {
                                $selectedOptions[] = $opt;
                            }
                        }
                        if (!empty($selectedOptions)) {
                            $selectedVariations[] = [
                                'type' => $group['type'] ?? null,
                                'selected' => $selectedOptions,
                            ];
                        }
                    }
                }
            }

            // resolve product name safely
            $prodModel = $productId ? Product::find($productId) : null;
            $productName = $prodModel ? $prodModel->name : null;

            return [
                'product_id' => $productId,
                'name' => $productName,
                'quantity' => $quantity,
                'price' => $price,
                'notes' => $notes,
                'selected_variations' => $selectedVariations, // only selected items
            ];
        };

        // log order_type change
        if ((string)$oldOrderType !== (string)$orderType) {
            OrderLog::create([
                'order_id' => $order->id,
                'event_type' => 'order_type',
                'old_value' => (string)$oldOrderType,
                'new_value' => (string)$orderType,
                'performed_by' => $performedBy,
                'performed_by_id' => $performedById,
                'meta' => 'order_type',
            ]);
        }

        // log payment_method change
        if ((string)$oldPaymentMethod !== (string)$paymentMethod) {
            OrderLog::create([
                'order_id' => $order->id,
                'event_type' => 'payment_method',
                'old_value' => (string)$oldPaymentMethod,
                'new_value' => (string)$paymentMethod,
                'performed_by' => $performedBy,
                'performed_by_id' => $performedById,
                'meta' => 'payment_method',
            ]);
        }

        // products: ADDED (single entry, show name + selected variation only)
        foreach ($added as $p) {
            $newPayload = $buildSelectedPayload($p);
            OrderLog::create([
                'order_id' => $order->id,
                'event_type' => 'product_added',
                'old_value' => null,
                'new_value' => json_encode($newPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'performed_by' => $performedBy,
                'performed_by_id' => $performedById,
                'meta' => 'products',
            ]);
        }

        // products: REMOVED (single entry, include selected variation from old payload)
        foreach ($removed as $p) {
            $oldPayload = $buildSelectedPayload($p);
            OrderLog::create([
                'order_id' => $order->id,
                'event_type' => 'product_removed',
                'old_value' => json_encode($oldPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'new_value' => null,
                'performed_by' => $performedBy,
                'performed_by_id' => $performedById,
                'meta' => 'products',
            ]);
        }

        // products: UPDATED (single entry per product; old -> new; only selected variation values are stored)
        foreach ($updated as $p) {
            $oldPayload = $buildSelectedPayload($p['old']);
            $newPayload = $buildSelectedPayload($p['new']);
            // If payloads are identical (no meaningful selected change), skip logging to avoid duplicates
            if (json_encode($oldPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) === json_encode($newPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) {
                continue;
            }
            OrderLog::create([
                'order_id' => $order->id,
                'event_type' => 'product_updated',
                'old_value' => json_encode($oldPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'new_value' => json_encode($newPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'performed_by' => $performedBy,
                'performed_by_id' => $performedById,
                'meta' => 'products',
            ]);
        }

        // ...existing code continues (invoice booking notify email response) ...
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

        $oldStatus = $order->status;

        $order->update([
            'status' => $data['status'],
        ]);

        // 🔥 Log this status change (create if missing, update if exists)
        OrderLog::updateOrCreate(
            [
                'order_id' => $order->id,
                'event_type' => 'status_change',
            ],
            [
                'old_value' => $oldStatus,
                'new_value' => $data['status'],
                'performed_by' => auth()->user()->name ?? 'System',
                'performed_by_id' => auth()->id(),
            ]
        );

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

        $oldPaymentStatus = $order->is_paid ? 'Paid' : 'Unpaid';
        $newPaymentStatus = $request->is_paid ? 'Paid' : 'Unpaid';

        $order->update([
            'is_paid' => $request->is_paid,
        ]);

        // 🔥 Log this payment status change (create if missing, update if exists)
        OrderLog::updateOrCreate(
            [
                'order_id' => $order->id,
                'event_type' => 'payment_status',
            ],
            [
                'old_value' => $oldPaymentStatus,
                'new_value' => $newPaymentStatus,
                'performed_by' => auth()->user()->name ?? 'System',
                'performed_by_id' => auth()->id(),
            ]
        );

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

    public function events($id)
    {
        $order = Order::with('logs')->withTrashed()->find($id); // include soft-deleted

        if (!$order) {
            return ServiceResponse::error('Order not found');
        }

        $events = $order->logs->map(function ($log) {
            return [
                'id' => $log->id,
                'event_type' => $log->event_type,
                'old_value' => $log->old_value,
                'new_value' => $log->new_value,
                'performed_by' => $log->performed_by,
                'performed_by_id' => $log->performed_by_id,
                'timestamp' => $log->created_at->toDateTimeString(),
            ];
        });

        return ServiceResponse::success('Order events fetched successfully', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'created_at' => $order->created_at ? $order->created_at->toDateTimeString() : null,
            'updated_at' => $order->updated_at ? $order->updated_at->toDateTimeString() : null,
            'deleted_at' => $order->deleted_at ? $order->deleted_at->toDateTimeString() : null,
            'events' => $events,
        ]);
    }

    public function updateEvent(Request $request, $orderId, $eventId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return ServiceResponse::error('Order not found');
        }

        $event = $order->logs()->find($eventId);

        if (!$event) {
            return ServiceResponse::error('Event not found for this order');
        }

        $event->update([
            'event_type' => $request->input('event_type', $event->event_type),
            'old_value' => $request->input('old_value', $event->old_value),
            'new_value' => $request->input('new_value', $event->new_value),
            'performed_by' => $request->input('performed_by', $event->performed_by),
            'performed_by_id' => $request->input('performed_by_id', $event->performed_by_id),
        ]);

        return ServiceResponse::success('Event updated successfully', [
            'event' => $event,
        ]);
    }
}
