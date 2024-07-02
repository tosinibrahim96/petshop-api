<?php

namespace Tests\Feature;

use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Support\Facades\Password;

class AdminAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful login for an admin user.
     *
     * @return void
     */
    public function testAdminUserCanLogin()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt($password = 'password'),
            'is_admin' => true,
        ]);

        $response = $this->postJson('/api/v1/admin/login', [
            'email' => 'admin@example.com',
            'password' => $password,
        ]);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'token',
                         'user' => [
                             'uuid',
                             'first_name',
                             'last_name',
                             'email',
                             'is_admin',
                         ],
                     ],
                 ]);
    }

    /**
     * Test failed login with invalid credentials.
     *
     * @return void
     */
    public function testAdminUserCannotLoginWithInvalidCredentials()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $response = $this->postJson('/api/v1/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Invalid login credentials',
                 ]);
    }

    /**
     * Test forgot password for a valid email.
     *
     * @return void
     */
    public function testForgotPasswordWithValidEmail()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->postJson('/api/v1/admin/forgot-password', [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'status' => true,
                     'message' => 'A password reset link has been sent to user@example.com',
                 ]);
    }

    /**
     * Test forgot password with an invalid email.
     *
     * @return void
     */
    public function testForgotPasswordWithInvalidEmail()
    {
        $response = $this->postJson('/api/v1/admin/forgot-password', [
            'email' => 'invalid@example.com',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
                 ->assertJson([
                     'status' => false,
                     'message' => 'The email address provided is not associated with a user account',
                 ]);
    }

    /**
     * Test reset password with valid token and email.
     *
     * @return void
     */
    public function testResetPasswordWithValidToken()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $token = "token_".strtotime(now());
        PasswordResetToken::factory()->create(['token' => $token, 'email' => 'user@example.com']);

        $response = $this->postJson('/api/v1/admin/reset-password-token', [
            'email' => 'user@example.com',
            'token' => $token,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Password reset successful',
                 ]);
    }

    /**
     * Test reset password with invalid token.
     *
     * @return void
     */
    public function testResetPasswordWithInvalidToken()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this->postJson('/api/v1/admin/reset-password-token', [
            'email' => 'user@example.com',
            'token' => 'invalid-token',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
                 ->assertJson([
                     'status' => false,
                     'message' => 'Invalid token or email',
                 ]);
    }

    /**
     * Test logout for an authenticated admin user.
     *
     * @return void
     */
    public function testAdminUserCanLogout()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $this->actingAs($admin, 'api');

        $response = $this->getJson('/api/v1/admin/logout');

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Successfully logged out',
                 ]);
    }

    /**
     * Test that a non-admin user cannot access the create endpoint.
     *
     * @return void
     */
    public function testNonAdminUserCannotAccessCreateEndpoint()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/v1/admin/create', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
                 ->assertJson([
                     'message' => 'Unauthorized',
                 ]);
    }

    /**
     * Test that an unauthenticated user cannot access the create endpoint.
     *
     * @return void
     */
    public function testUnauthenticatedUserCannotAccessCreateEndpoint()
    {
        $response = $this->postJson('/api/v1/admin/create', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
                 ->assertJson([
                     'message' => 'Unauthenticated.',
                 ]);
    }
}
