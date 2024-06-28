<?php

declare(strict_types=1);

use Lcobucci\JWT\Signer\Key\InMemory;

return [
    'private_key' => InMemory::file(base_path('keys/private_key.pem')),

    'public_key' => InMemory::file(base_path('keys/public_key.pem')),

    /**
     * In hours
     */
    'expiration' => env('JWT_EXPIRATION', 2)
];
