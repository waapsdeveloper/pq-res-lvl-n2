<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\StoreOrder;
use App\Http\Requests\Admin\Order\UpdateOrder;
use App\Http\Requests\Admin\Order\UpdateOrderStatus;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Payments;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Carbon\Carbon;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $orders = Order::with('orderProducts.product')->get();
        // $order = OrderResource::collection($orders);
        // return ServiceResponse::success('Order list retrieved successfully', $order);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perpage = $request->input('perpage', 10);
        $filters = $request->input('filters', null);

        $category = $request->input('category_id', '');

        $query = Order::query()->with('customer', 'table_no', 'orderProducts', 'table')->with(['orderProducts.productProp'])->orderBy('id', 'desc');
        // dd($query);
        // $query->with('orderProducts.product')->with('orderProducts.productProps');

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
        $data = $request->all();
        // $data = $request->validated();
        // dd($data);

        $customerName = $data['customer_name'] ?? 'Walk-in Customer';
        $customerPhone = $data['customer_phone'] ?? 'XXXX';
        $rtableIdf = $request->input('table_identifier', null);

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
            }

            $variations = isset($item['variation']) ? json_decode($item['variation'], true) : null;
            $productVariationPrice = 0;

            if ($variations) {
                foreach ($variations as $variation) {
                    if (isset($variation['options']) && is_array($variation['options']) && $variation['options'] === true) {
                        foreach ($variation['options'] as $option) {
                            $productVariationPrice += $option['price'] ?? 0;
                        }
                    }
                }
            }

            $pricePerUnit = $item['price'] + $productVariationPrice;
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
        $type = $rtableIdf ? 'dine-in' : $data['type'];
        // $finalPrice = $totalPrice - ($totalPrice * ($discount / 100));
        $finalPrice = $totalPrice - $discount;
        $orderNumber = strtoupper(uniqid('ORD-'));
        $orderNote = $request->notes;
        $orderStatus = $request->status;
        $uniqid = uniqid();
        $order = Order::create([
            'identifier' => null,
            'order_number' => 'ORD-' . $uniqid,
            'type' => $type,
            'status' => $orderStatus,
            'notes' => $orderNote,
            'customer_id' => $user->id ?? null,
            'discount' => $discount,
            'invoice' => 'INV-' . $uniqid,
            'table_no' => $rtableIdf ?? null,
            'total_price' => $finalPrice,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // return  response()->json($orderProducts);
        foreach ($orderProducts as $orderProduct) {
            OrderProduct::create([
                // return,
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
        // return response()->json($data, $order, $orderProducts);
        $order->load('orderProducts.product');

        $data = new OrderResource($order);

        return ServiceResponse::success("Order list successfully", ['data' => $data]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch the order with its related products and restaurant
        $order = Order::where('id', $id)
            ->with('orderProducts.product', 'restaurant')
            ->with('customer', 'table_no', 'table')->with(['orderProducts.productProp'])
            ->first();

        if (!$order) {
            return ServiceResponse::error('Order not found');
        }
        // dd($order->toArray());
        // Get product details for all products in the order


        // Transform the order using OrderResource
        $data = new OrderResource($order);
        // Add the products to the resource

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
            return ServiceResponse::error("Order not found.");
        }

        $totalPrice = 0;
        $orderProducts = [];

        foreach ($data['products'] as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                return ServiceResponse::error("Product with ID {$item['product_id']} not found.");
            }

            $variations = isset($item['variation']) ? json_decode($item['variation'], true) : null;
            $productVariationPrice = 0;

            if ($variations) {
                foreach ($variations as $variation) {
                    if (isset($variation['options']) && is_array($variation['options'])) {
                        foreach ($variation['options'] as $option) {
                            $productVariationPrice += $option['price'] ?? 0;
                        }
                    }
                }
            }

            $pricePerUnit = $item['price'] + $productVariationPrice;
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

        $discount = $data['discount'] ?? $order->discount;
        $type = $order->table_no ? 'dine-in' : $data['type'];
        $tableNo = $data['tableNo'] ?? null;
        $finalPrice = $totalPrice - $discount;

        $order->update([
            'type' => $type,
            'status' => $request->status,
            'notes' => $request->notes,
            'customer_id' => $order->user_id ?? null,
            'discount' => $discount,
            'table_no' => $tableNo,
            'total_price' => $finalPrice,
            'updated_at' => now(),
        ]);

        // Delete existing order products
        $order->orderProducts()->delete();

        // Add updated order products
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
        // $orderProducts = OrderProduct::where('order_id', $order->id)->delete();

        if (!$order) {
            return ServiceResponse::error('Order not found');
        }


        $order->status = $request->status;
        $order->save();

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
