<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Latest electronic gadgets'],
            ['name' => 'Fashion', 'description' => 'Trendy clothing and accessories'],
            ['name' => 'Home & Living', 'description' => 'Furniture and home decor'],
            ['name' => 'Beauty', 'description' => 'Cosmetics and personal care'],
            ['name' => 'Sports', 'description' => 'Sports equipment and gear'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']) . '-' . Str::random(4),
                'description' => $category['description'],
                'image' => null,  // Update with actual images if available
            ]);
        }
    }
}
