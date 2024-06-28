<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PasswordResetToken;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PasswordResetTokenFactory extends Factory
{
    protected $model = PasswordResetToken::class;

    /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
    public function definition(): array
    {
        return [
            'email' => $this->faker->safeEmail,
            'token' => Str::random(60),
            'created_at' => now(),
        ];
    }
}
