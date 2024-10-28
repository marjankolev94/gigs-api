<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function register(array $data): User;
    public function updateUser(User $user, array $data): User;
}