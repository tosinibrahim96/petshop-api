<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = new UserRepository();
    }

    public function testCreateUser()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'address' => '123 Main St',
            'phone_number' => '555-555-5555'
        ];

        $user = $this->userRepository->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('john.doe@example.com', $user->email);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertNotNull($user->email_verified_at);
    }

    public function testFindByEmail()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com'
        ]);

        $foundUser = $this->userRepository->findByEmail('john.doe@example.com');

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function testFindByEmailReturnsNullWhenUserNotFound()
    {
        $foundUser = $this->userRepository->findByEmail('nonexistent@example.com');

        $this->assertNull($foundUser);
    }

    public function testFindUserById()
    {
        $user = User::factory()->create();

        $foundUser = $this->userRepository->find($user->id);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function testFindUserByIdReturnsNullWhenUserNotFound()
    {
        $foundUser = $this->userRepository->find(9999);

        $this->assertNull($foundUser);
    }

    public function testUpdateUser()
    {
        $user = User::factory()->create();
        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ];

        $updatedUser = $this->userRepository->update($data, $user->id);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertEquals('Jane', $updatedUser->first_name);
        $this->assertEquals('Doe', $updatedUser->last_name);
    }

    public function testUpdateUserReturnsNullWhenUserNotFound()
    {
        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ];

        $updatedUser = $this->userRepository->update($data, 9999);

        $this->assertNull($updatedUser);
    }

    public function testDeleteUser()
    {
        $user = User::factory()->create();

        $result = $this->userRepository->delete($user->id);

        $this->assertTrue($result);
        $this->assertNull(User::find($user->id));
    }

    public function testDeleteUserReturnsFalseWhenUserNotFound()
    {
        $result = $this->userRepository->delete(9999);

        $this->assertFalse($result);
    }
}
