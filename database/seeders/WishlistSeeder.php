<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    public function run()
    {
        User::chunk(100, function ($users) {
            $products = Product::pluck('id');

            foreach ($users as $user) {
                $wishlistItems = rand(0, 5);

                for ($i = 0; $i < $wishlistItems; $i++) {
                    Wishlist::firstOrCreate([
                        'user_id' => $user->id,
                        'product_id' => $products->random(),
                    ]);
                }
            }
        });
    }
}
