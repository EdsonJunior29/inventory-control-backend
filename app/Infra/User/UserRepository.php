<?php

namespace App\Infra\User;

use App\Domain\IRepository\IUserRepository;
use App\Enums\RoleType;
use App\Models\User;


class UserRepository implements IUserRepository
{
    public function createUser(string $name, string $email, string $password, RoleType $roleType) : User 
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        $user->roles()->attach($roleType->value);

        return $user;
    }

    public function getUserByEmail(string $userEmail) : ?User
    {
        return User::where('email', $userEmail)->first();
    }
}
