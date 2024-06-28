<?php

namespace Tests\Unit\Actions\ShopUser;

use App\Actions\ShopUser\DeleteUserAction;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteUserActionTest extends TestCase
{
    use RefreshDatabase;

    protected $userRepository;
    protected $deleteUserAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->deleteUserAction = new DeleteUserAction($this->userRepository);
    }

    public function testExecuteDeletesUser()
    {
        $userId = 1;

        $this->userRepository->method('delete')->willReturn(true);

        $result = $this->deleteUserAction->execute($userId);

        $this->assertTrue($result);
    }

    public function testExecuteFailsToDeleteUser()
    {
        $userId = 9999;

        $this->userRepository->method('delete')->willReturn(false);

        $result = $this->deleteUserAction->execute($userId);

        $this->assertFalse($result);
    }
}
