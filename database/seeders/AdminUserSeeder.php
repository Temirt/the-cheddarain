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
                'password' => Hash::make('Cheddarain2026!'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'kitchen@thecheddarain.com'],
            [
                'name' => 'Kitchen Staff Lead',
                'phone' => '15552433328',
                'role' => 'staff',
                'password' => Hash::make('Kitchen2026!'),
            ]
        );
    }
}
