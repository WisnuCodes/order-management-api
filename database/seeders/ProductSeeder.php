<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $sellerId = DB::table('User')->insertGetId([
            'name' => 'John Designer',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'role' => 'seller',
            'balance' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('User')->insert([
            'name' => 'Jane Buyer',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'role' => 'buyer',
            'balance' => 500000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat Category
        $cat1 = DB::table('Product_Category')->insertGetId([
            'name' => 'UI Design',
            'description' => 'Template dan asset untuk UI Design',
            'icon' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $cat2 = DB::table('Product_Category')->insertGetId([
            'name' => 'Source Code',
            'description' => 'Template source code aplikasi',
            'icon' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat Products
        DB::table('Product')->insert([
            [
                'seller_id' => $sellerId,
                'category_id' => $cat1,
                'title' => 'UI Kit Dashboard Pro',
                'description' => 'Complete UI kit for admin dashboard',
                'price' => 150000,
                'rating' => 8.7,
                'thumbnail' => 'uikit.jpg',
                'file_path' => 'files/uikit.zip',
                'download_count' => 45,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => $sellerId,
                'category_id' => $cat2,
                'title' => 'Laravel Starter Kit',
                'description' => 'Boilerplate Laravel dengan auth & CRUD',
                'price' => 250000,
                'rating' => 9.2,
                'thumbnail' => 'laravel-kit.jpg',
                'file_path' => 'files/laravel-starter.zip',
                'download_count' => 120,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
