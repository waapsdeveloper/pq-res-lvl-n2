<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ServiceResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\StoreOrder;
use App\Http\Requests\Admin\Order\UpdateOrder;
use App\Http\Requests\Admin\Order\UpdateOrderStatus;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $category = $request->input('category_id', '');

        $query = Order::query();

        $query->with('orderProducts.product');

        // Optionally apply search filter if needed
        if ($search) {
            // $query->where('name', 'like', '%' . $search . '%');
        }

        // Paginate the results
        $query->orderBy('id', 'desc');
        $data = $query->paginate($perpage, ['*'], 'page', $page);

        // Loop through the results and generate full URL for image
        $data->getCollection()->transform(function ($item) {
            return new OrderResource($item);
        });

        // Return the response with image URLs included
        return self::success("Order list successfully", ['data' => $data]);

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

        $data = $request->validated();

        $customerName = $data['customer_name'] ?? 'Walk-in Customer';
        $customerPhone = $data['customer_phone'] ?? 'XXXX';

        $totalPrice = 0;
        $orderProducts = [];
        // dd($data['products']);
        foreach ($data['products'] as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                continue;
                // return self::failure("Product with ID {$item['product_id']} not found.");
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
                'notes' => $item['notes'],
            ];
        }

        $discount = $data['discount'] ?? 0;
        // $finalPrice = $totalPrice - ($totalPrice * ($discount / 100));
        $finalPrice = $totalPrice - $discount;
        // dd($finalPrice);
        $orderNumber = strtoupper(uniqid('ORD-'));
        $orderNote = $request->notes;
        $orderStatus = $request->status;
        $order = Order::create([
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'discount' => $discount,
            'order_number' => $orderNumber,
            'total_price' => $finalPrice,
            "notes" => $orderNote,
            'status' => $orderStatus,
        ]);

        foreach ($orderProducts as $orderProduct) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $orderProduct['product_id'],
                'quantity' => $orderProduct['quantity'],
                'price' => $orderProduct['price'],
                'notes' => $orderProduct['notes'],
            ]);
        }

        $order->load('orderProducts.product');

        $data = new OrderResource($order);

        return self::success("Order list successfully", ['data' => $data]);
    }






    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $order = Order::where('id', $id)->with('orderProducts.product')->first();

        if (!$order) {
            return ServiceResponse::error('Order not found');
        }

        return ServiceResponse::success('Order details fetched successfully', ['order' => new OrderResource($order)]);
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
            return self::failure("Order with ID $id not found.");
        }


        $customerName = $data['customer_name'] ?? $order->customer_name;
        $customerPhone = $data['customer_phone'] ?? $order->customer_phone;

        $totalPrice = 0;
        $orderProducts = [];
        // Process products
        foreach ($data['products'] as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                continue; // Ignore invalid products
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
                'notes' => $item['notes'],

            ];
        }

        // Calculate discount and final price
        $discount = $data['discount'] ?? $order->discount;
        // $finalPrice = $totalPrice - ($totalPrice * ($discount / 100));
        $finalPrice = $totalPrice - $discount;

        // Update order details
        $order->update([
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'discount' => $discount,
            'total_price' => $finalPrice,
            'status' => $data['status'] ?? $order->status,
            'notes' => $data['notes'] ?? $order->notes,
        ]);

        // Update order products
        // First, delete old products
        $order->orderProducts()->delete();

        // Then, insert new ones
        foreach ($orderProducts as $orderProduct) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $orderProduct['product_id'],
                'quantity' => $orderProduct['quantity'],
                'price' => $orderProduct['price'],
                'notes' => $orderProduct['notes'],
            ]);
        }

        // Reload the updated order with its products
        $order->load('orderProducts.product');

        $data = new OrderResource($order);

        return self::success("Order updated successfully", ['data' => $data]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateStatus(UpdateOrderStatus $request, $id)
    {
        $data = $request->validated();


        $order = Order::find($id);

        if (!$order) {
            return ServiceResponse::error('Order not found');
        }


        $order->status = $request->status;
        $order->save();

        return ServiceResponse::success('Order status updated successfully', $order);
    }
}
