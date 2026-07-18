<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@dibitech.com'],
            [
                'name' => 'Super Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'balance' => 0
            ]
        );
    }
}
