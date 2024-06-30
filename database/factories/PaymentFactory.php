<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class PaymentFactory
 *
 */
class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['credit_card', 'cash_on_delivery', 'bank_transfer']);
        $details = [];

        switch ($type) {
            case 'credit_card':
                $details = [
                    'holder_name' => $this->faker->name,
                    'number' => $this->faker->creditCardNumber,
                    'ccv' => $this->faker->randomNumber(3),
                    'expire_date' => $this->faker->creditCardExpirationDateString,
                ];
                break;
            case 'cash_on_delivery':
                $details = [
                    'first_name' => $this->faker->firstName,
                    'last_name' => $this->faker->lastName,
                    'address' => $this->faker->address,
                ];
                break;
            case 'bank_transfer':
                $details = [
                    'swift' => $this->faker->swiftBicNumber,
                    'iban' => $this->faker->iban('US'),
                    'name' => $this->faker->name,
                ];
                break;
        }

        return [
            'uuid' => $this->faker->uuid,
            'type' => $type,
            'details' => json_encode($details),
        ];
    }
}
