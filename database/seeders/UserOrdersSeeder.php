<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;

/**
 * Class UserOrdersSeeder
 *
 * @package Database\Seeders
 */
class UserOrdersSeeder extends Seeder
{
    /**
     * Seed the orders for users.
     *
     * @return void
     */
    public function run()
    {
        
        $users = User::factory()->count(10)->create();
        
        if(OrderStatus::count() < 1){
            OrderStatus::factory()->pending()->create();
            OrderStatus::factory()->paid()->create();
            OrderStatus::factory()->shipped()->create();
            OrderStatus::factory()->cancelled()->create();
        }
        
        if(Product::count() < 10){
            $products = Product::factory()->count(50)->create();
        }
        
        $orderCount = 50;

        foreach ($users as $user) {
            for ($i = 0; $i < $orderCount / count($users); $i++) {
                $status = OrderStatus::inRandomOrder()->first();
                Order::factory()->create([
                    'user_id' => $user->id,
                    'order_status_id' => $status->id,
                    'payment_id' => in_array($status->title, ['paid', 'shipped']) ? Payment::factory()->create()->id : null,
                    'products' => json_encode($this->generateRandomProducts($products)),
                ]);
            }
        }
    }

    /**
     * Generate random products with quantities.
     *
     * @param  \Illuminate\Support\Collection $products
     * @return array
     */
    private function generateRandomProducts($products)
    {
        $randomProducts = $products->random(rand(1, 5))->pluck('uuid')->toArray();
        $productList = [];

        foreach ($randomProducts as $productUuid) {
            $productList[] = [
                'product' => $productUuid,
                'quantity' => rand(1, 10),
            ];
        }

        return $productList;
    }
}
