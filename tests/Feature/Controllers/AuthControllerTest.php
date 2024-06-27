<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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
}
