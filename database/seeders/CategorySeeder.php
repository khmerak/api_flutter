<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Adidas', 'image' => 'adidas.png'],
            ['name' => 'Fila', 'image' => 'Fila.png'],
            ['name' => 'Nike', 'image' => 'Nike1.png'],
            ['name' => 'Puma', 'image' => 'puma.png'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
