<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AuthResource
 * Transforms the authentication response data.
 *
 * @OA\Schema(
 *     schema="AuthResource",
 *     type="object",
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(property="uuid", type="string"),
 *         @OA\Property(property="first_name", type="string"),
 *         @OA\Property(property="last_name", type="string"),
 *         @OA\Property(property="email", type="string", format="email"),
 *         @OA\Property(property="is_admin", type="boolean")
 *     ),
 *     @OA\Property(property="token", type="string")
 * )
 */
class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this->user;

        return [
            'user' => [
                'uuid' => $user->uuid,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
            ],
            'token' => $this->when(isset($this->token), $this->token),
        ];
    }
}
