<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserRepository
 * Handles the data logic for the User model.
 */
class UserRepository
{
    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['email_verified_at'] = Carbon::now();
        return User::create($data);
    }

    /**
     * Find a user by email.
     *
     * @param string $email User email
     * @return User|null The user instance or null if not found
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Find a user by ID.
     *
     * @param int $id User ID
     * @return User|null The user instance or null if not found
     */
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Update a user by ID.
     *
     * @param array $data User data to update
     * @param int $id User ID
     * @return User|null The updated user instance or null if not found
     */
    public function update(array $data, int $id): ?User
    {
        $user = $this->find($id);
        if ($user) {
            $user->update($data);
        }
        return $user;
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id User ID
     * @return bool True if the user was deleted, false otherwise
     */
    public function delete(int $id): bool
    {
        return User::destroy($id) > 0;
    }
}
