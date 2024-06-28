<?php

namespace Tests\Unit\Actions\ShopUser;

use App\Actions\ShopUser\ShowUserAction;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowUserActionTest extends TestCase
{
    use RefreshDatabase;

    protected $userRepository;
    protected $showUserAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->showUserAction = new ShowUserAction($this->userRepository);
    }

    public function testExecuteShowsUser()
    {
        $userId = 1;

        $user = new User();
        $user->id = $userId;

        $this->userRepository->method('find')->willReturn($user);

        $foundUser = $this->showUserAction->execute($userId);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($userId, $foundUser->id);
    }

    public function testExecuteReturnsNullWhenUserNotFound()
    {
        $userId = 9999;

        $this->userRepository->method('find')->willReturn(null);

        $foundUser = $this->showUserAction->execute($userId);

        $this->assertNull($foundUser);
    }
}
