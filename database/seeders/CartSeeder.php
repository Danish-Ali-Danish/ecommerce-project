<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run()
    {
        User::chunk(100, function ($users) {
            $products = Product::pluck('id');

            foreach ($users as $user) {
                $cartItems = rand(0, 5);

                Cart::factory($cartItems)->create([
                    'user_id' => $user->id,
                    'product_id' => fn() => $products->random(),
                ]);
            }
        });
    }
}
