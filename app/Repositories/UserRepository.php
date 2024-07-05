<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

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


    /**
     * Get paginated list of non-admin users.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getPaginatedUsersWithoutAdmins(array $filters)
    {
        $query = User::where('is_admin', false);

        $this->applyFilters($query, $filters);

        $limit = $filters['limit'] ?? 15;

        return $query->paginate($limit);        
    }



    /**
     * Apply filters to the query.
     *
     * @param Builder $query
     * @param array $filters
     * @return void
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        if (isset($filters['sort_by'])) {
            $direction = isset($filters['desc']) && filter_var($filters['desc'], FILTER_VALIDATE_BOOLEAN) ? 'desc' : 'asc';
            $query->orderBy($filters['sort_by'], $direction);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('first_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('last_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }
    }

    /**
     * Find a user by UUID.
     *
     * @param string $uuid
     * @return User|null
     */
    public function findByUuid(string $uuid): ?User
    {
        return User::where('uuid', $uuid)->first();
    }
}
