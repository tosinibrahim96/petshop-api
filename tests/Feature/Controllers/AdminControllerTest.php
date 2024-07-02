<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a new admin account as an authenticated admin user.
     *
     * @return void
     */
    public function testCreateAdminAccountAsAdmin()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $requestData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => 'Lagos, Nigeria',
            'phone_number' => '(248) 565-2474',
        ];

        $response = $this->postJson('api/v1/admin/create', $requestData);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'token',
                'user' => [
                    'uuid',
                    'first_name',
                    'last_name',
                    'email',
                    'is_admin'
                ],
            ],
        ]);

        $response->assertJson([
            'status' => true,
            'message' => 'Account created successfully',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
            'is_admin' => true,
        ]);

        $this->assertEquals(2, User::count());
    }

    /**
     * Test that a non-admin user cannot create an admin account.
     *
     * @return void
     */
    public function testNonAdminUserCannotCreateAdminAccount()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        $requestData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => 'Lagos, Nigeria',
            'phone_number' => '(248) 565-2474',
        ];

        $response = $this->postJson('api/v1/admin/create', $requestData);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Unauthorized',
        ]);
    }

    /**
     * Test that an unauthenticated user cannot create an admin account.
     *
     * @return void
     */
    public function testUnauthenticatedUserCannotCreateAdminAccount()
    {
        $requestData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => 'Lagos, Nigeria',
            'phone_number' => '(248) 565-2474',
        ];

        $response = $this->postJson('api/v1/admin/create', $requestData);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    /**
     * Test creating an admin account with invalid input data.
     *
     * @return void
     */
    public function testCreateAdminAccountWithInvalidInput()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $requestData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => 'Lagos, Nigeria',
            'phone_number' => '(248) 565-2474',
        ];

        $response = $this->postJson('api/v1/admin/create', $requestData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email',
            ],
        ]);
        $response->assertJson([
            'message' => 'The email field is required.',
        ]);
    }
}
