<?php

declare(strict_types=1);

namespace App\Actions\ShopUser;

use App\Repositories\UserRepository;

/**
 * Class CreateUserAction
 * Handles the logic for creating a new user.
 *
 */
class CreateUserAction
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * CreateUserAction constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the action to create a new user.
     *
     * @param array $data
     * @return User
     */
    public function execute(array $data)
    {
        return $this->userRepository->create($data);
    }
}
