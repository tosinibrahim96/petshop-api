<?php

namespace Tests\Feature\Controllers;

use App\Models\PasswordResetToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Password;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password')
        ]);
    }

    public function testLoginSuccessfully()
    {
        $data = [
            'email' => 'john.doe@example.com',
            'password' => 'password'
        ];

        $response = $this->postJson('/api/v1/user/login', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'email' => 'john.doe@example.com'
                    ]
                ]
            ]);
    }

    public function testLoginFailure()
    {
        $data = [
            'email' => 'john.doe@example.com',
            'password' => 'wrong_password'
        ];

        $response = $this->postJson('/api/v1/user/login', $data);

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Invalid login credentials'
            ]);
    }

    public function testForgotPasswordValidEmail()
    {
        $user = User::factory()->create(['email' => 'john.doe+1@example.com']);

        $response = $this->postJson('/api/v1/user/forgot-password', ['email' => 'john.doe@example.com']);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => "A password reset link has been sent to john.doe@example.com"
                 ]);
    }

    public function testForgotPasswordInvalidEmail()
    {
        $response = $this->postJson('/api/v1/user/forgot-password', ['email' => 'invalid@example.com']);

        $response->assertStatus(400)
                 ->assertJson([
                     'status' => false,
                     'message' => 'The email address provided is not associated with a user account'
                 ]);
    }

    public function testResetPasswordWithValidToken()
    {
        User::factory()->create(['email' => 'john.doe+2@example.com']);
        $token = 'sample_token';
        PasswordResetToken::factory()->create(['token' => $token, 'email' => 'john.doe+2@example.com']);

        $response = $this->postJson('/api/v1/user/reset-password-token', [
            'email' => 'john.doe+2@example.com',
            'token' => $token,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Password reset successful'
                 ]);
    }

    public function testResetPasswordWithInvalidToken()
    {
        $user = User::factory()->create(['email' => 'john.doe+3@example.com']);

        $response = $this->postJson('/api/v1/user/reset-password-token', [
            'email' => 'john.doe+3@example.com',
            'token' => 'invalid-token',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Invalid token or email'
                 ]);
    }

    public function testResetPasswordWithInvalidEmail()
    {
        $user = User::factory()->create(['email' => 'john.doe+4@example.com']);
        $token = Password::createToken($user);

        $response = $this->postJson('/api/v1/user/reset-password-token', [
            'email' => 'invalid@example.com',
            'token' => $token,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'message' => 'The selected email is invalid.'
                 ]);
    }

    public function testLogout()
    {
        $user = User::factory()->create();
        Auth::attempt(['email' => $user->email, 'password' => 'userpassword']);

        $response = $this->getJson('/api/v1/user/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Successfully logged out'
                 ]);

        $this->assertFalse(Auth::check());
        $this->assertNull(Auth::user());
    }
}
