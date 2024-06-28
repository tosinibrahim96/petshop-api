<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateUserSuccessfully()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => '123 Main St',
            'phone_number' => '555-555-5555'
        ];

        $response = $this->postJson('/api/v1/user/create', $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Account created successfully',
                     'data' => [
                         'user' => [
                             'email' => 'john.doe@example.com'
                         ]
                     ]
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com'
        ]);
    }

    public function testCreateUserValidationFailure()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'password_confirmation' => 'wrong_password',
            'address' => '123 Main St',
            'phone_number' => '555-555-5555'
        ];

        $response = $this->postJson('/api/v1/user/create', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    public function testShowUserSuccessfully()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->getJson('/api/v1/user');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'User retrieved successfully',
                     'data' => [
                        'uuid' => $user->uuid,
                        'email' => $user->email
                     ]
                 ]);
    }

    public function testShowUserUnauthenticated()
    {
        $response = $this->getJson('/api/v1/user');

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Unauthenticated.'
                 ]);
    }

    public function testUpdateUserSuccessfully()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user, 'api');

        $data = [
            'email' => 'john.doe@example.com',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'address' => '456 Elm St'
        ];

        $response = $this->putJson('/api/v1/user/edit', $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'User updated successfully',
                     'data' => [
                        'uuid' => $user->uuid,
                        'first_name' => 'Jane',
                        'last_name' => 'Doe',
                        'address' => '456 Elm St'
                     ]
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'address' => '456 Elm St'
        ]);
    }

    public function testUpdateUserValidationFailure()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user, 'api');

        $data = [
            'first_name' => '',
            'last_name' => '',
            'email' => ''
        ];

        $response = $this->putJson('/api/v1/user/edit', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['first_name', 'last_name', 'email']);
    }

    public function testUpdateUserUnauthenticated()
    {
        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'address' => '456 Elm St'
        ];

        $response = $this->putJson('/api/v1/user/edit', $data);

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Unauthenticated.'
                 ]);
    }

    public function testDeleteUserSuccessfully()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user, 'api');

        $response = $this->deleteJson('/api/v1/user');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'User deleted successfully'
                 ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    public function testDeleteUserUnauthenticated()
    {
        $response = $this->deleteJson('/api/v1/user');

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Unauthenticated.'
                 ]);
    }
}
