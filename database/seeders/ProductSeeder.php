<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get dynamic category IDs
        $adidas = Category::where('name', 'Adidas')->first()?->id;
        $fila   = Category::where('name', 'Fila')->first()?->id;
        $nike   = Category::where('name', 'Nike')->first()?->id;
        $puma   = Category::where('name', 'Puma')->first()?->id;

        $products = [
            [
                'title' => 'Running Shoes',
                'price' => 59.99,
                'stock' => 20,
                'category_id' => $adidas,
                'description' => 'Comfortable running shoes.',
                'image' => 'products/p4.png',
            ],
            [
                'title' => 'Casual T-Shirt',
                'price' => 15.99,
                'stock' => 50,
                'category_id' => $fila,
                'description' => 'Soft cotton T-shirt.',
                'image' => 'products/top1.png',
            ],
            [
                'title' => 'Smartwatch',
                'price' => 120.00,
                'stock' => 15,
                'category_id' => $nike,
                'description' => 'Waterproof smartwatch.',
                'image' => 'products/model.png',
            ],
            [
                'title' => 'Backpack',
                'price' => 25.50,
                'stock' => 30,
                'category_id' => $puma,
                'description' => 'Durable school backpack.',
                'image' => 'products/top2.png',
            ],
            [
                'title' => 'Sunglasses',
                'price' => 18.00,
                'stock' => 40,
                'category_id' => $adidas,
                'description' => 'UV protection sunglasses.',
                'image' => 'products/Mask_Group2.png',
            ],
            [
                'title' => 'Wireless Earbuds',
                'price' => 45.00,
                'stock' => 25,
                'category_id' => $nike,
                'description' => 'Bluetooth earbuds with noise canceling.',
                'image' => 'products/Mask_Group3.png',
            ],
            [
                'title' => 'Polo Shirt',
                'price' => 22.00,
                'stock' => 35,
                'category_id' => $fila,
                'description' => 'High-quality polo shirt.',
                'image' => 'products/p4.png',
            ],
            [
                'title' => 'Leather Belt',
                'price' => 12.00,
                'stock' => 28,
                'category_id' => $puma,
                'description' => 'Genuine leather belt.',
                'image' => 'products/p5.png',
            ],
            [
                'title' => 'Travel Bag',
                'price' => 38.00,
                'stock' => 12,
                'category_id' => $adidas,
                'description' => 'Large travel bag.',
                'image' => 'products/r1.png',
            ],
            [
                'title' => 'Sport Socks',
                'price' => 5.00,
                'stock' => 60,
                'category_id' => $fila,
                'description' => 'Sweat-resistant sport socks.',
                'image' => 'products/small11.png',
            ],
        ];

        Product::insert($products);
    }
}
