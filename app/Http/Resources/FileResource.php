<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * FileResource
 *
 * @OA\Schema(
 *     schema="FileResource",
 *     type="object",
 *     title="FileResource",
 *     description="File resource",
 *     @OA\Property(property="uuid", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
 *     @OA\Property(property="name", type="string", example="example.jpg"),
 *     @OA\Property(property="path", type="string", example="pet-shop/example.jpg"),
 *     @OA\Property(property="size", type="integer", example=1024),
 *     @OA\Property(property="type", type="string", example="image/jpeg")
 * )
 */
class FileResource extends JsonResource
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
            'uuid' => $this->uuid,
            'name' => $this->name,
            'path' => $this->path,
            'size' => $this->size,
            'type' => $this->type,
        ];
    }
}
