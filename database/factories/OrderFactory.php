<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\OrderStatus;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class OrderFactory
 *
 * @package Database\Factories
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'order_status_id' => OrderStatus::factory(),
            'payment_id' => function (array $attributes) {
                $status = OrderStatus::find($attributes['order_status_id']);
                if (in_array($status->title, ['paid', 'shipped'])) {
                    return Payment::factory();
                }
                return null;
            },
            'uuid' => $this->faker->uuid,
            'products' => json_encode([
                ['product' => $this->faker->uuid, 'quantity' => $this->faker->numberBetween(1, 10)]
            ]),
            'address' => json_encode([
                'billing' => $this->faker->address,
                'shipping' => $this->faker->address,
            ]),
            'delivery_fee' => $this->faker->randomFloat(2, 0, 50),
            'amount' => $this->faker->randomFloat(2, 50, 500),
            'shipped_at' => $this->faker->dateTime,
        ];
    }
}
