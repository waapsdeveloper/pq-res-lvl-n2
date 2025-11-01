<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ServiceResponse;
use App\Models\Product;
use App\Models\Category;
use App\Http\Resources\Frontend\ProductResource;
use App\Http\Resources\Admin\CategoryResource;

use App\Models\RestaurantMeta;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Invoice;
use App\Models\User;
use App\Helpers\Identifier;
use App\Helpers\Helper;
use App\Http\Resources\Admin\OrderResource;
use App\Models\BranchConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



class KioskController extends Controller
{
  public function getCatalog(request $request)
  {
    $products =  Product::where('restaurant_id', $request->restaurant_id)->get();
    $categories =  Category::where('restaurant_id', $request->restaurant_id)->get();
    return ServiceResponse::success(
      'Kiosk catalog fetched successfully',
      [
        'products' => ProductResource::collection($products),
        'categories' => CategoryResource::collection($categories)
      ]
    );
    // Implementation for fetching kiosk catalog


  }

  /**
   * Return restaurant meta data for a given restaurant_id
   */
  public function getRestaurantMeta(Request $request)
  {
    $meta = RestaurantMeta::where('restaurant_id', $request->restaurant_id)->get();

    // Fetch tax and delivery_charges from BranchConfigTable
    $branchConfig = BranchConfig::where('branch_id', $request->restaurant_id)
      ->first();

    $tax = $branchConfig->tax ?? null;
    $delivery_charges = $branchConfig->delivery_charges ?? null;

    return ServiceResponse::success(
      'Restaurant meta fetched successfully',
      [
        'meta' => $meta,
        'tax' => $tax,
        'delivery_charges' => $delivery_charges
      ]
    );
  }

  /**
   * Create order from kiosk frontend
   * Expected payload: restaurant_id, products: [{product_id, price, quantity, variation, notes, category}], optional customer_name/customer_phone, discount, payment_method, order_type, delivery_address
   */
  public function createOrder(Request $request)
  {
    try {
      $data = $request->all();

      // basic validation
      if (empty($data['products']) || !is_array($data['products'])) {
        return ServiceResponse::error('Invalid products payload');
      }

      $resID = $request->restaurant_id;

      $customerName = $data['customer_name'] ?? 'Walk-in Customer';
      $customerPhone = $data['customer_phone'] ?? 'XXXX';

      // find or create user by phone
      $user = User::where('phone', $customerPhone)->first();
      if (!$user) {
        $user = User::create([
          'name' => $customerName,
          'phone' => $customerPhone,
          'email' => $customerPhone . "@phone.test",
        ]);
      }

      // Build order products and totals
      $totalPrice = 0;
      $orderProducts = [];
      foreach ($data['products'] as $item) {
        $productId = $item['product_id'] ?? null;
        $pricePerUnit = $item['price'] ?? 0;
        $quantity = $item['quantity'] ?? 1;
        $itemTotal = $pricePerUnit * $quantity;
        $totalPrice += $itemTotal;

        $orderProducts[] = [
          'product_id' => $productId,
          'quantity' => $quantity,
          'price' => $pricePerUnit,
          'notes' => $item['notes'] ?? null,
          'variation' => isset($item['variation']) ? json_encode($item['variation'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
          'category' => $item['category'] ?? null,
        ];
      }

      $discount = $data['discount'] ?? 0;
      $finalPrice = $totalPrice - $discount;

      // prepare order fields
      $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(str()->random(6));

      $order = Order::create([
        'identifier' => 'ORD-',
        'restaurant_id' => $resID,
        'order_number' => $orderNumber,
        'type' => $data['type'] ?? null,
        'status' => $data['status'] ?? 'pending',
        'notes' => $data['notes'] ?? null,
        'customer_id' => $user->id ?? null,
        'discount' => $discount,
        'invoice' => 'INV-',
        'table_no' => $data['table_id'] ?? null,
        'total_price' => $finalPrice,
        'payment_method' => $data['payment_method'] ?? null,
        'order_type' => $data['order_type'] ?? null,
        'delivery_address' => $data['delivery_address'] ?? null,
        'coupon_code' => $data['coupon_code'] ?? null,
        'discount' => $data['discount_value'] ?? null,
        'final_total' => $data['final_total'] ?? $finalPrice,
        'tax_percentage' => $data['tax_percentage'] ?? 0,
        'tax_amount' => $data['tax_amount'] ?? 0,
        'tips' => $data['tips'] ?? 0,
        'tips_amount' => $data['tips_amount'] ?? 0,
        'delivery_charges' => $data['delivery_charges'] ?? 0,
        'source' => 'kiosk',
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      // set identifiers
      $identifier = Identifier::make('Order', $order->id, 3);
      $invoice_no = Identifier::make('Invoice', $order->id, 3);
      $order->update(['identifier' => $identifier, 'invoice_no' => $invoice_no]);

      // create order products
      foreach ($orderProducts as $op) {
        OrderProduct::create(array_merge($op, ['order_id' => $order->id, 'created_at' => now(), 'updated_at' => now()]));
      }

      $order->load('orderProducts.product');

      // create invoice record
      $invoice = Invoice::create([
        'order_id' => $order->id,
        'invoice_no' => $order->invoice_no,
        'invoice_date' => now(),
        'restaurant_id' => $resID,
        'payment_method' => $data['payment_method'] ?? null,
        'total' => $order->total_price,
        'status' => 'pending',
        'notes' => "",
      ]);

      // notify -- reuse helper if available
      try {
        Helper::sendEmail($user->email, 'Your Order Details', 'emails.order_details', ['order' => $order]);
      } catch (\Exception $e) {
        // swallow email errors for kiosk
      }

      $data = new OrderResource($order);
      return ServiceResponse::success('Order created successfully', ['data' => $data]);
    } catch (\Exception $e) {
      // Log full exception for debugging and return a safe error message
      Log::error('Kiosk createOrder error', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'payload' => $request->all()]);
      return ServiceResponse::error('Server error: ' . $e->getMessage());
    }
  }
}
