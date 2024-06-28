<?php

namespace Tests\Unit\Actions\ShopUser;

use App\Actions\ShopUser\CreateUserAction;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateUserActionTest extends TestCase
{
    use RefreshDatabase;

    protected $userRepository;
    protected $createUserAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->createUserAction = new CreateUserAction($this->userRepository);
    }

    public function testExecuteCreatesUser()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'address' => '123 Main St',
            'phone_number' => '555-555-5555'
        ];

        $user = new User($data);
        $user->id = 1;

        $this->userRepository->method('create')->willReturn($user);

        $createdUser = $this->createUserAction->execute($data);

        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertEquals('john.doe@example.com', $createdUser->email);
        $this->assertTrue(Hash::check('password', $createdUser->password));
    }
}
