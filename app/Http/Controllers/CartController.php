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
            'products.*.quantity' => 'required|integer|min:1', // Validate quantity
        ]);

        // Find or create a cart for the user
        $cart = Cart::firstOrCreate(['user_id' => $validatedData['user_id']]);

        // Clear old entries
        $cart->cartProducts()->delete();

        // Attach the new products with quantities
        foreach ($validatedData['products'] as $product) {
            $cart->cartProducts()->create([
                'product_id' => $product['id'],
                'quantity' => $product['quantity'], // Include quantity
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
