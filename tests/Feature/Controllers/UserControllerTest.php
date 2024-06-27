<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

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
}
