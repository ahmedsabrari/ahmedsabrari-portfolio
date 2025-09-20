<?php

namespace App\Repositories\Eloquent\Auth;

use App\Models\User;
use App\Repositories\Contracts\Auth\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getQuery()
    {
        return User::query();
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function count(): int
    {
        return User::count();
    }

    public function countByStatus(string $status): int
    {
        return User::where('status', $status)->count();
    }

    public function countByRole(string $role): int
    {
        return User::where('role', $role)->count();
    }
}