<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *     title="User",
 *     @OA\Property(property="uuid", type="string", example="3fa85f64-5717-4562-b3fc-2c963f66afa6"),
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", example="2021-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="avatar", type="string", example="http://example.com/avatar.jpg"),
 *     @OA\Property(property="address", type="string", example="123 Main St"),
 *     @OA\Property(property="phone_number", type="string", example="555-555-5555"),
 *     @OA\Property(property="is_marketing", type="boolean", example=false),
 *     @OA\Property(property="last_login_at", type="string", format="date-time", example="2021-01-01T00:00:00.000000Z")
 * )
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'avatar' => $this->avatar,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'is_marketing' => $this->is_marketing,
            'last_login_at' => $this->last_login_at,
        ];
    }
}
