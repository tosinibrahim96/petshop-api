<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Repositories\UserRepository;
use App\Services\Auth\JwtService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginAction
 * Handles the authentication logic for logging in a user.
 *
 */
class LoginAction
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var JwtService
     */
    protected $jwtService;

    /**
     * LoginAction constructor.
     *
     * @param UserRepository $userRepository
     * @param JwtService $jwtService
     */
    public function __construct(UserRepository $userRepository, JwtService $jwtService)
    {
        $this->userRepository = $userRepository;
        $this->jwtService = $jwtService;
    }

    /**
     * Execute the login action.
     *
     * @param array $data
     * @return array|null
     */
    public function execute(array $data)
    {
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return null;
        }

        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return null;
        }

        $token = $this->jwtService->createToken([
            'sub' => $user->id,
            'user_uuid' => $user->uuid
        ]);

        $user->last_login_at = Carbon::now();
        $user->save();

        return ['user' => $user, 'token' => $token];
    }
}
