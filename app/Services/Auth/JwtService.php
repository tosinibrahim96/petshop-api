<?php

declare(strict_types=1);

namespace App\Services\Auth;

use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;

/**
 * Service for handling JWT operations such as creation, parsing, and validation.
 */
class JwtService
{
    /**
     * @var Configuration
     */
    private $config;

    /**
     * JwtService constructor.
     *
     * Initializes the JWT configuration with asymmetric RSA keys.
     */
    public function __construct()
    {
        $privateKey = config('jwt.private_key');
        $publicKey = config('jwt.public_key');

        $this->config = Configuration::forAsymmetricSigner(
            new Sha256(),
            $privateKey,
            $publicKey
        );
    }

    /**
     * Creates a JWT with the given claims.
     *
     * @param array<string, non-empty-string> $claims The claims to be included in the JWT.
     * @return string The generated JWT.
     */
    public function createToken(array $claims): string
    {
        $now = new DateTimeImmutable();

        $builder = $this->config->builder()
            ->issuedBy(config('app.url'))
            ->relatedTo((string) $claims['sub'])
            ->identifiedBy(bin2hex(random_bytes(16)))
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+' . config('jwt.expiration') . ' hour'));

        foreach ($claims as $key => $value) {
            if ($key !== 'sub' && !empty($key)) {
                $builder->withClaim($key, $value);
            }
        }

        return $builder->getToken($this->config->signer(), $this->config->signingKey())->toString();
    }

    /**
     * Parses a JWT string into an UnencryptedToken object.
     *
     * @param non-empty-string $jwt The JWT string to parse.
     * @return Token The parsed JWT.
     */
    public function parseToken(string $jwt): Token
    {
        return $this->config->parser()->parse($jwt);
    }

    /**
     * Validates a given JWT.
     *
     * @param Token $token The JWT to validate.
     * @return bool True if the JWT is valid, false otherwise.
     */
    public function validateToken(Token $token): bool
    {
        $constraints = $this->config->validationConstraints();
        $constraints[] = new IssuedBy(config('app.url'));

        return $this->config->validator()->validate($token, ...$constraints);
    }
}
