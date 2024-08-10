<?php

namespace App\Infra\User;

use App\Domain\Entities\User as EntitiesUser;
use App\Domain\IRepository\IUserRepository;
use App\Enums\Profiles;
use App\Models\User;


class UserRepository implements IUserRepository
{
    public function createUser(EntitiesUser $entitieUser, Profiles $profileType) : void
    {
        $user = User::create([
            'name' => $entitieUser->getName(),
            'email' => $entitieUser->getEmail(),
            'password' => $entitieUser->getPassword()
        ]);

        $user->profiles()->attach($profileType->value);
    }

    public function getUserByEmail(string $userEmail)
    {
        return User::where('email', $userEmail)
            ->with('profiles')
            ->first();
    }
}
