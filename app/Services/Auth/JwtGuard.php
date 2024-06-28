<?php

declare(strict_types=1);

namespace App\Services\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;

/**
 * JWT Guard implementation for Laravel authentication.
 */
class JwtGuard implements Guard
{
    /**
     * @var mixed The currently authenticated user.
     */
    protected $user;

    /**
     * @var UserProvider The user provider instance.
     */
    protected $provider;

    /**
     * @var Configuration The JWT configuration instance.
     */
    protected $config;

    /**
     * @var Request The current HTTP request.
     */
    protected $request;

    /**
     * JwtGuard constructor.
     *
     * @param UserProvider $provider The user provider instance.
     */
    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;

        $privateKey = config('jwt.private_key');
        $publicKey = config('jwt.public_key');

        $this->config = Configuration::forAsymmetricSigner(
            new Sha256(),
            $privateKey,
            $publicKey
        );
    }

    /**
     * Get the currently authenticated user.
     *
     * @return mixed|null The authenticated user or null if not authenticated.
     */
    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        $token = $this->getTokenForRequest();
        if (!$token) {
            return null;
        }

        try {
            $jwtService = app(JwtService::class);
            $token = $jwtService->parseToken($token);

            if ($jwtService->validateToken($token)) {
                $claims = $token->claims();
                $this->user = $this->provider->retrieveById($claims->get('sub'));
                return $this->user;
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    /**
     * Get the token for the current request.
     *
     * @return string|null The token from the request or null if not present.
     */
    public function getTokenForRequest()
    {
        $token = request()->bearerToken();

        if (empty($token)) {
            $token = request()->input('token');
        }

        return $token;
    }

    /**
     * Attempt to authenticate a user with the given credentials.
     *
     * If authentication is successful and $login is true, a JWT token will be generated and returned.
     * Otherwise, it will return true if authentication is successful and false otherwise.
     *
     * @param array<string, mixed> $credentials The user credentials for authentication.
     * @param bool $login Whether to generate and return a JWT token upon successful authentication.
     * @return bool Returns false if authentication fails, true if authentication succeeds.
     */
    public function attempt(array $credentials = [], bool $login = true): bool
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($user && $this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);
            return true;
        }

        return false;
    }

    /**
     * Validate user credentials.
     *
     * @param array<string, mixed> $credentials The user credentials.
     * @return bool True if the credentials are valid, false otherwise.
     */
    public function validate(array $credentials = [])
    {
        return (bool) $this->attempt($credentials, false);
    }

    /**
     * Check if the user is authenticated.
     *
     * @return bool True if the user is authenticated, false otherwise.
     */
    public function check()
    {
        return !is_null($this->user());
    }

    /**
     * Check if the current user is a guest.
     *
     * @return bool True if the user is a guest, false otherwise.
     */
    public function guest()
    {
        return !$this->check();
    }

    /**
     * Get the ID of the currently authenticated user.
     *
     * @return int|string|null The ID of the authenticated user or null if not authenticated.
     */
    public function id()
    {
        if ($this->user()) {
            return $this->user()->getAuthIdentifier();
        }

        return null;
    }

    /**
     * Set the current user.
     *
     * @param mixed $user The user instance.
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Determine if the guard has a user instance.
     *
     * @return bool True if the guard has a user instance, false otherwise.
     */
    public function hasUser(): bool
    {
        return $this->user ? true : false;
    }

    /**
     * Invalidate the current token.
     *
     * @return void
     */
    public function logout()
    {
        $token = $this->getTokenForRequest();

        if ($token) {
            app(JwtService::class)->blacklistToken($token);
        }

        $this->user = null;
    }
}
