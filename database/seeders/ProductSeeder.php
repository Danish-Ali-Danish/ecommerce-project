<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();

        $featuredProducts = [
            [
                'name' => 'Smartphone X Pro',
                'description' => 'Flagship smartphone with advanced camera',
                'price' => 99999,
                'discount_price' => 89999,
                'stock' => 50,
                'sku' => 'SPX-PRO-2025',
                'image' => 'products/smartphone-x-pro.jpg',
                'is_featured' => true,
                'is_new' => true,
                'category_id' => $categories->firstWhere('name', 'Electronics')->id,
            ],
            // ... more sample products
        ];

        foreach ($featuredProducts as $product) {
            Product::create(array_merge($product, [
                'slug' => Str::slug($product['name']) . '-' . Str::random(4),
            ]));
        }

        // Generate 50 random products
        Product::factory(50)->create();
    }
}
