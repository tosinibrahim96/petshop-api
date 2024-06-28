<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Repositories\PasswordResetTokenRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Str;
use App\Jobs\SendPasswordResetEmail;

/**
 * Class CreatePasswordResetTokenAction
 */
class CreatePasswordResetTokenAction
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var PasswordResetTokenRepository
     */
    protected $passwordResetTokenRepository;

    /**
     * CreatePasswordResetTokenAction constructor.
     *
     * @param UserRepository $userRepository
     * @param PasswordResetTokenRepository $passwordResetTokenRepository
     */
    public function __construct(
        UserRepository $userRepository,
        PasswordResetTokenRepository $passwordResetTokenRepository
    ) {
        $this->userRepository = $userRepository;
        $this->passwordResetTokenRepository = $passwordResetTokenRepository;
    }

    /**
     * Execute the action to create a password reset token.
     *
     * @param string $email
     * @return bool
     */
    public function execute(string $email): bool
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            return false;
        }

        $token = hash('sha256', Str::random(60).strtotime(now()->toDateTimeString()));
        $this->passwordResetTokenRepository->updateOrInsertToken($email, $token);

        SendPasswordResetEmail::dispatch($email, $token);

        return true;
    }
}
