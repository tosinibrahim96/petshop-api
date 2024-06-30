<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Collection;

/**
 * Class OrderRepository
 * Handles the data logic for the User model.
 */
class OrderRepository
{
    /**
     * Get all orders for a given user.
     *
     * @param int $userId
     * @param array $relations
     * @return \Illuminate\Support\Collection
     */
    public function getOrdersForUser(int $userId, ?array $relations = []): Collection
    {
        return Order::with($relations)->where('user_id', $userId)->get();
    }
}
