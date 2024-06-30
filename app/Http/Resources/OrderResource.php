<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="OrderResource",
 *     type="object",
 *     title="OrderResource",
 *     description="Order resource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="order_status_id", type="integer", example=1),
 *     @OA\Property(property="payment_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="uuid", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
 *     @OA\Property(
 *         property="products",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="product", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
 *             @OA\Property(property="quantity", type="integer", example=2)
 *         )
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="object",
 *         @OA\Property(property="billing", type="string", example="123 Billing St"),
 *         @OA\Property(property="shipping", type="string", example="123 Shipping St")
 *     ),
 *     @OA\Property(property="delivery_fee", type="number", format="float", nullable=true, example=5.99),
 *     @OA\Property(property="amount", type="number", format="float", example=99.99),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="shipped_at", type="string", format="date-time", nullable=true, example="2023-01-02T00:00:00.000000Z"),
 *     @OA\Property(property="order_status", type="string"),
 *     @OA\Property(property="payment", ref="#/components/schemas/PaymentResource", nullable=true)
 * )
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_status_id' => $this->order_status_id,
            'payment_id' => $this->payment_id,
            'uuid' => $this->uuid,
            'products' => json_decode($this->products),
            'address' => json_decode($this->address),
            'delivery_fee' => $this->delivery_fee,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'shipped_at' => $this->shipped_at,
            'order_status' => isset($this->orderStatus) ? $this->orderStatus->title: null,
            'payment' => isset($this->payment) ? PaymentResource::make($this->payment):null
        ];
    }
}
