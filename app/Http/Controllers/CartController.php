<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'products' => 'required|array',
            'products.*.id' => 'required|integer',
        ]);

        // Find or create a cart for the user
        $cart = Cart::firstOrCreate(['user_id' => $validatedData['user_id']]);

        // Sync the products in the cart
        $productIds = collect($validatedData['products'])->pluck('id')->toArray();

        // Clear old entries and attach the new ones
        $cart->cartProducts()->delete();
        foreach ($productIds as $productId) {
            $cart->cartProducts()->create([
                'product_id' => $productId,
            ]);
        }

        // Return the cart with all its products and their details
        $cart->load(['cartProducts.product']); // Eager load the nested relationship

        return response()->json([
            'message' => 'Cart updated successfully',
            'cart' => $cart,
        ]);
    }
}
