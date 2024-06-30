<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PaymentResource",
 *     type="object",
 *     title="PaymentResource",
 *     description="Payment resource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="uuid", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
 *     @OA\Property(property="type", type="string", example="credit_card"),
 *     @OA\Property(
 *         property="details",
 *         type="object",
 *         description="Payment details specific to the payment type",
 *         @OA\Property(
 *             property="holder_name",
 *             type="string",
 *             example="John Doe"
 *         ),
 *         @OA\Property(
 *             property="number",
 *             type="string",
 *             example="4111111111111111"
 *         ),
 *         @OA\Property(
 *             property="ccv",
 *             type="integer",
 *             example=123
 *         ),
 *         @OA\Property(
 *             property="expire_date",
 *             type="string",
 *             example="12/25"
 *         ),
 *         @OA\Property(
 *             property="first_name",
 *             type="string",
 *             example="John"
 *         ),
 *         @OA\Property(
 *             property="last_name",
 *             type="string",
 *             example="Doe"
 *         ),
 *         @OA\Property(
 *             property="address",
 *             type="string",
 *             example="123 Main St"
 *         ),
 *         @OA\Property(
 *             property="swift",
 *             type="string",
 *             example="BOFAUS3N"
 *         ),
 *         @OA\Property(
 *             property="iban",
 *             type="string",
 *             example="US64SVBKUS6S3300958879"
 *         ),
 *         @OA\Property(
 *             property="name",
 *             type="string",
 *             example="John Doe"
 *         )
 *     )
 * )
 */
class PaymentResource extends JsonResource
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
            'uuid' => $this->uuid,
            'type' => $this->type,
            'details' => json_decode($this->details, true)
        ];
    }
}
