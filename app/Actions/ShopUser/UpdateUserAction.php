<?php

declare(strict_types=1);

namespace App\Actions\ShopUser;

use App\Repositories\UserRepository;
use App\Models\User;

/**
 * Class UpdateUserAction
 */
class UpdateUserAction
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UpdateUserAction constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the action to update a user.
     *
     * @param array $data
     * @param int $userId
     * @return User|null
     */
    public function execute(array $data, int $userId): ?User
    {
        return $this->userRepository->update($data, $userId);
    }
}
