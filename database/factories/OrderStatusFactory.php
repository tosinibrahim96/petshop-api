<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class OrderStatusFactory
 *
 */
class OrderStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $statuses = [
            'pending',
            'paid',
            'shipped',
            'cancelled',
        ];

        return [
            'uuid' => $this->faker->uuid,
            'title' => $this->faker->unique()->randomElement($statuses),
        ];
    }

    /**
     * Indicate that the model's title should be pending.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function pending()
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'pending',
        ]);
    }

    /**
     * Indicate that the model's title should be paid.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function paid()
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'paid',
        ]);
    }

    /**
     * Indicate that the model's title should be shipped.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function shipped()
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'shipped',
        ]);
    }

    /**
     * Indicate that the model's title should be cancelled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelled()
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'cancelled',
        ]);
    }
}
