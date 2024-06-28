<?php

declare(strict_types=1);

namespace App\Actions\ShopUser;

use App\Repositories\UserRepository;
use App\Models\User;

/**
 * Class ShowUserAction
 */
class ShowUserAction
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ShowUserAction constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the action to show a user.
     *
     * @param ?int $userId
     * @return User|null
     */
    public function execute(?int $userId): ?User
    {
        return $this->userRepository->find($userId);
    }
}
