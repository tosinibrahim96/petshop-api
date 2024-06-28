<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PasswordResetToken;
use Illuminate\Support\Carbon;

/**
 * Class PasswordResetTokenRepository
 * @package App\Repositories
 */
class PasswordResetTokenRepository
{
    /**
     * Update or insert a password reset token.
     *
     * @param string $email
     * @param string $token
     * @return void
     */
    public function updateOrInsertToken(string $email, string $token): void
    {
        PasswordResetToken::updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );
    }

    /**
     * Find a password reset token record by token.
     *
     * @param string $token
     * @return PasswordResetToken|null
     */
    public function findByToken(string $token): ?PasswordResetToken
    {
        return PasswordResetToken::where('token', $token)->first();
    }

    /**
     * Delete a password reset token record by email.
     *
     * @param string $email
     * @return void
     */
    public function deleteByEmail(string $email): void
    {
        PasswordResetToken::where('email', $email)->delete();
    }
}
