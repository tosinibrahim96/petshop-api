<?php

declare(strict_types=1);

namespace App\Actions\ShopUser;

use App\Repositories\UserRepository;

/**
 * Class DeleteUserAction
 */
class DeleteUserAction
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * DeleteUserAction constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the action to delete a user.
     *
     * @param int $userId
     * @return bool
     */
    public function execute(int $userId): bool
    {
        return $this->userRepository->delete($userId);
    }
}
