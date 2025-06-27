<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@shopnow.com',
            'password' => Hash::make('password'),
            'phone' => '03001234567',
            'address' => '123 Main Street, Lahore, Pakistan',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Regular users
        User::factory(10)->create();
    }
}
