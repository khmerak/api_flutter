<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItems;
use App\Models\Orders;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Create order
        $order = Orders::create([
            'user_id' => 1,
            'total_amount' => 149.97,
            'status' => 'completed',
            'payment_method' => 'cash',
        ]);

        // Order items
        OrderItems::create([
            'order_id' => $order->id,
            'product_id' => 1,
            'quantity' => 2,
            'price' => 19.99 * 2,
        ]);

        OrderItems::create([
            'order_id' => $order->id,
            'product_id' => 3,
            'quantity' => 1,
            'price' => 79.99,
        ]);
    }
}
