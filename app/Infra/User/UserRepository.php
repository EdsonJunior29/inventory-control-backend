<?php

namespace App\Infra\User;

use App\Domain\IRepository\IUserRepository;
use App\Models\User;


class UserRepository implements IUserRepository
{
    public function createUser(string $name, string $email, string $password) : User 
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
    }

    public function getUserByEmail(string $userEmail) : User
    {
        return User::where('email', $userEmail)->first();
    }
}
