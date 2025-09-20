<?php

namespace App\Repositories\Contracts\Auth;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getQuery();
    public function find(int $id): ?User;
    public function create(array $data): User;
    public function update(User $user, array $data): User;
    public function delete(User $user): bool;
    public function count(): int;
    public function countByStatus(string $status): int;
    public function countByRole(string $role): int;
}