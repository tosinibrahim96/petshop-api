<?php

namespace Tests\Unit\Repositories;

use App\Models\PasswordResetToken;
use App\Repositories\PasswordResetTokenRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTokenRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $passwordResetTokenRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->passwordResetTokenRepository = new PasswordResetTokenRepository();
    }

    public function testUpdateOrInsertToken()
    {
        $email = 'john.doe@example.com';
        $token1 = 'sample_token_1';
        $token2 = 'sample_token_2';

        $this->passwordResetTokenRepository->updateOrInsertToken($email, $token1);
        $passwordResetToken = PasswordResetToken::where('email', $email)->first();

        $this->assertNotNull($passwordResetToken);
        $this->assertEquals($email, $passwordResetToken->email);
        $this->assertEquals($token1, $passwordResetToken->token);

        $this->passwordResetTokenRepository->updateOrInsertToken($email, $token2);
        $passwordResetToken = PasswordResetToken::where('email', $email)->first();

        $this->assertNotNull($passwordResetToken);
        $this->assertEquals($email, $passwordResetToken->email);
        $this->assertEquals($token2, $passwordResetToken->token);

        $tokenCount = PasswordResetToken::where('email', $email)->count();
        $this->assertEquals(1, $tokenCount);
    }

    public function testFindByToken()
    {
        $token = 'sample_token';
        PasswordResetToken::factory()->create(['token' => $token]);

        $foundToken = $this->passwordResetTokenRepository->findByToken($token);

        $this->assertInstanceOf(PasswordResetToken::class, $foundToken);
        $this->assertEquals($token, $foundToken->token);
    }

    public function testFindByTokenReturnsNullWhenTokenNotFound()
    {
        $foundToken = $this->passwordResetTokenRepository->findByToken('nonexistent_token');

        $this->assertNull($foundToken);
    }

    public function testDeleteByEmail()
    {
        $email = 'john.doe@example.com';
        PasswordResetToken::factory()->create(['email' => $email]);

        $this->passwordResetTokenRepository->deleteByEmail($email);

        $deletedToken = PasswordResetToken::where('email', $email)->first();

        $this->assertNull($deletedToken);
    }

    public function testDeleteByEmailReturnsFalseWhenEmailNotFound()
    {
        $email = 'nonexistent@example.com';

        $this->passwordResetTokenRepository->deleteByEmail($email);

        $deletedToken = PasswordResetToken::where('email', $email)->first();

        $this->assertNull($deletedToken);
    }
}
