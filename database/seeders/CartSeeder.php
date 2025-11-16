<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\CartItem;

class CartSeeder extends Seeder
{
    // public function run(): void
    // {
    //     $cart1 = Cart::create([
    //         'user_id' => 1,
    //     ]);

    //     CartItem::insert([
    //         [
    //             'cart_id' => $cart1->id,
    //             'product_id' => 1,
    //             'quantity' => 1,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'cart_id' => $cart1->id,
    //             'product_id' => 2,
    //             'quantity' => 2,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //     ]);

    //     $cart2 = Cart::create([
    //         'user_id' => 2,
    //     ]);

    //     CartItem::insert([
    //         [
    //             'cart_id' => $cart2->id,
    //             'product_id' => 3,
    //             'quantity' => 1,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //     ]);
    // }
}
