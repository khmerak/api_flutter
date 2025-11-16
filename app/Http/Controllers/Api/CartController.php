<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Get all cart items for a user
    public function index($user_id)
    {
        // Find cart for this user
        $cart = Cart::where('user_id', $user_id)->first();

        if (!$cart) {
            return response()->json([]);
        }

        // Get cart items with product details
        $items = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get();

        return response()->json($items);
    }



    // Add or increase quantity
    public function store(Request $request)
    {
        $userId = $request->user_id;
        $productId = $request->product_id;

        // 1. Find or create cart for user
        $cart = Cart::firstOrCreate([
            'user_id' => $userId
        ]);

        // 2. Check if product already in cart
        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingItem) {
            // Increase quantity by 1
            $existingItem->quantity += 1;
            $existingItem->save();

            return response()->json([
                'message' => 'Quantity increased',
                'item' => $existingItem
            ], 200);
        }

        // 3. Add new cart item
        $item = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $productId,
            'quantity' => 1
        ]);

        return response()->json([
            'message' => 'Added to cart',
            'item' => $item
        ], 201);
    }


    // Update quantity
    public function update(Request $request, $id)
    {
        $item = CartItem::find($id);

        if (!$item) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $item->quantity = $request->quantity;
        $item->save();

        return response()->json([
            'message' => 'Quantity updated',
            'item' => $item
        ], 200);
    }

    // Remove an item
    public function destroy($id)
    {
        $item = CartItem::find($id);

        if (!$item) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $item->delete();

        return response()->json(['message' => 'Removed']);
    }


    // Clear entire cart for a user
    public function clear($user_id)
    {
        Cart::where('user_id', $user_id)->delete();
        return response()->json(['message' => 'Cart cleared'], 200);
    }
}
