<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate(
            [
                'email' => 'admin@buckhill.co.uk'
            ],
            [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'password' => Hash::make('admin'),
                'is_admin' => true,
                'email_verified_at' => now(),
                'phone_number' => fake()->phoneNumber(),
            ]
        );
    }
}
