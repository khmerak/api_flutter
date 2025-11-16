<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\cart;
use App\Models\CartItem;
use App\Models\OrderItems;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order = Orders::create([
            'user_id' => $request->user_id,
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
        ]);

        foreach ($request->items as $item) {
            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'order' => $order
        ], 200);
    }


    public function show($id)
    {
        return Orders::with('items.product')->findOrFail($id);
    }

    public function checkout(Request $request)
    {
        // 🔍 Validate input
        $request->validate([
            'user_id' => 'required|integer',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'items' => 'required|array',
        ]);

        $userId = $request->user_id;
        $items = $request->items;

        if (empty($items)) {
            return response()->json(['message' => 'No items selected'], 400);
        }

        try {
            // 🚀 Use Transaction to ensure everything succeeds
            DB::beginTransaction();

            // 🧾 Create Order
            $order = Orders::create([
                'user_id' => $userId,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
            ]);

            // 🛒 Add Items to Order + Remove from Cart
            foreach ($items as $item) {
                // Save order item
                OrderItems::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Delete the cart item
                CartItem::where('id', $item['cart_item_id'])->delete();
            }

            // 🧹 Clean up: remove empty cart (optional)
            $cart = Cart::where('user_id', $userId)->first();

            if ($cart) {
                $remaining = CartItem::where('cart_id', $cart->id)->count();
                if ($remaining == 0) {
                    $cart->delete(); // delete cart if no items left
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order_id' => $order->id
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Checkout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function countUserOrders($user_id)
    {
        $count = Orders::where('user_id', $user_id)->count();

        return response()->json([
            "success" => true,
            "orders" => $count
        ], 200);
    }
}
