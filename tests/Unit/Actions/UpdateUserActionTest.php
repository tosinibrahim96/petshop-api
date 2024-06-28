<?php

namespace Tests\Unit\Actions\ShopUser;

use App\Actions\ShopUser\UpdateUserAction;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateUserActionTest extends TestCase
{
    use RefreshDatabase;

    protected $userRepository;
    protected $updateUserAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->updateUserAction = new UpdateUserAction($this->userRepository);
    }

    public function testExecuteUpdatesUser()
    {
        $userId = 1;
        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ];

        $user = new User();
        $user->id = $userId;
        $user->first_name = 'John';
        $user->last_name = 'Doe';

        $updatedUser = new User();
        $updatedUser->id = $userId;
        $updatedUser->first_name = 'Jane';
        $updatedUser->last_name = 'Doe';

        $this->userRepository->method('find')->willReturn($user);
        $this->userRepository->method('update')->willReturn($updatedUser);

        $result = $this->updateUserAction->execute($data, $userId);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('Jane', $result->first_name);
        $this->assertEquals('Doe', $result->last_name);
    }

    public function testExecuteReturnsNullWhenUserNotFound()
    {
        $userId = 9999;
        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ];

        $this->userRepository->method('find')->willReturn(null);

        $result = $this->updateUserAction->execute($data, $userId);

        $this->assertNull($result);
    }
}
