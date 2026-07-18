<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $seller = DB::table('User')->where('email', 'john@example.com')->first();
        if (!$seller) {
            $sellerId = DB::table('User')->insertGetId([
                'name' => 'John Designer',
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'role' => 'seller',
                'balance' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $sellerId = $seller->user_id;
        }

        if (!DB::table('User')->where('email', 'jane@example.com')->exists()) {
            DB::table('User')->insert([
                'name' => 'Jane Buyer',
                'email' => 'jane@example.com',
                'password' => Hash::make('password123'),
                'role' => 'buyer',
                'balance' => 500000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Buat Category
        $cat1 = DB::table('Product_Category')->where('name', 'UI Design')->first();
        if (!$cat1) {
            $cat1Id = DB::table('Product_Category')->insertGetId([
                'name' => 'UI Design',
                'description' => 'Template dan asset untuk UI Design',
                'icon' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $cat1Id = $cat1->category_id;
        }

        $cat2 = DB::table('Product_Category')->where('name', 'Source Code')->first();
        if (!$cat2) {
            $cat2Id = DB::table('Product_Category')->insertGetId([
                'name' => 'Source Code',
                'description' => 'Template source code aplikasi',
                'icon' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $cat2Id = $cat2->category_id;
        }

        // Buat Products
        if (!DB::table('Product')->where('title', 'UI Kit Dashboard Pro')->exists()) {
            DB::table('Product')->insert([
                'seller_id' => $sellerId,
                'category_id' => $cat1Id,
                'title' => 'UI Kit Dashboard Pro',
                'description' => 'Complete UI kit for admin dashboard dengan ratusan komponen yang siap digunakan untuk mempercepat proses desain aplikasi web Anda.',
                'price' => 150000,
                'rating' => 8.7,
                'thumbnail' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80',
                'file_path' => 'files/uikit.zip',
                'download_count' => 45,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!DB::table('Product')->where('title', 'Laravel Starter Kit')->exists()) {
            DB::table('Product')->insert([
                'seller_id' => $sellerId,
                'category_id' => $cat2Id,
                'title' => 'Laravel Starter Kit',
                'description' => 'Boilerplate Laravel dengan fitur autentikasi, manajemen role, dan CRUD lengkap. Sangat cocok untuk memulai proyek skala besar.',
                'price' => 250000,
                'rating' => 9.2,
                'thumbnail' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=80',
                'file_path' => 'files/laravel-starter.zip',
                'download_count' => 120,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!DB::table('Product')->where('title', 'Minimalist E-Commerce App UI')->exists()) {
            DB::table('Product')->insert([
                'seller_id' => $sellerId,
                'category_id' => $cat1Id,
                'title' => 'Minimalist E-Commerce App UI',
                'description' => 'Desain modern, clean, dan elegan untuk aplikasi e-commerce. Dilengkapi dengan file Figma dan Prototype lengkap.',
                'price' => 180000,
                'rating' => 9.5,
                'thumbnail' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&w=600&q=80',
                'file_path' => 'files/ecommerce-ui.zip',
                'download_count' => 85,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
