<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $products = Product::all();

        foreach ($products as $product) {
            $reviewCount = rand(0, 10);

            for ($i = 0; $i < $reviewCount; $i++) {
                $user = $users->random();

                Review::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'rating' => rand(3, 5),
                    'comment' => $this->generateReviewText(),
                ]);
            }
        }
    }

    private function generateReviewText()
    {
        $phrases = [
            'Great product!',
            'Very satisfied with my purchase.',
            'Works as described.',
            'Excellent quality for the price.',
            'Would definitely buy again.',
            'Fast delivery and good packaging.',
            'Product met my expectations.',
            'Highly recommended!',
            'Good value for money.',
            'Better than I expected.',
        ];

        return $phrases[array_rand($phrases)];
    }
}
