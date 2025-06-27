<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $products = Product::all();
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentMethods = ['credit_card', 'cod', 'bank_transfer'];

        foreach ($users as $user) {
            $orderCount = rand(0, 5);

            for ($i = 0; $i < $orderCount; $i++) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => 'ORD-' . Str::upper(Str::random(8)),
                    'status' => $statuses[array_rand($statuses)],
                    'total_amount' => 0,  // Calculated later
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'status_history' => json_encode([
                        ['status' => 'pending', 'date' => now()]
                    ])
                ]);

                // Add 1-5 random products to order
                $items = $products->random(rand(1, 5));
                $total = 0;

                foreach ($items as $product) {
                    $quantity = rand(1, 3);
                    $price = $product->discount_price ?? $product->price;
                    $total += $price * $quantity;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $quantity,
                        'price' => $price
                    ]);
                }

                $order->update(['total_amount' => $total]);
            }
        }
    }
}
