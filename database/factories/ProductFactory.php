<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ProductFactory
 *
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'category_uuid' => Category::factory(),
            'uuid' => $this->faker->uuid,
            'title' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->paragraph,
            'metadata' => json_encode([
                'brand' => $this->faker->uuid,
                'image' => $this->faker->uuid
            ]),
        ];
    }
}
