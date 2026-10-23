<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@thecheddarain.com'],
            [
                'name' => 'The Cheddarain Manager',
                'phone' => '15552433327',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@thecheddarain.com'],
            [
                'name' => 'Michael Sterling',
                'phone' => '5551234567',
                'role' => 'customer',
                'password' => Hash::make('customer123'),
            ]
        );
    }
}
