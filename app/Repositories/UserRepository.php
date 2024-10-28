<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function register(array $data): User
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function updateUser(User $user, array $data): User
    {
        $user->update($data);
        
        return $user;
    }
}