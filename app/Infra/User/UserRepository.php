<?php

namespace App\Infra\User;

use App\Domain\IRepository\IUserRepository;
use App\Enums\Profiles;
use App\Models\User;


class UserRepository implements IUserRepository
{
    public function createUser(string $name, string $email, string $password, Profiles $profileType) : User 
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        $user->profiles()->attach($profileType->value);

        return $user;
    }

    public function getUserByEmail(string $userEmail) : ?User
    {
        return User::where('email', $userEmail)
            ->with('profiles')
            ->first();
    }
}
