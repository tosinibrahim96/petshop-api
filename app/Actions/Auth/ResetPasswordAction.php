<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Repositories\PasswordResetTokenRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

/**
 * Class ResetPasswordAction
 */
class ResetPasswordAction
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
     * ResetPasswordAction constructor.
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
     * Execute the action to reset the password using a token.
     *
     * @param array <string string> $data
     * @return bool
     */
    public function execute(array $data): bool
    {
        $record = $this->passwordResetTokenRepository->findByToken($data['token']);

        if (!$record || $record->email !== $data['email']) {
            return false;
        }

        $user = $this->userRepository->findByEmail($data['email']);
        $user->password = Hash::make($data['password']);
        $user->save();

        $this->passwordResetTokenRepository->deleteByEmail($data['email']);

        return true;
    }
}
