<?php

namespace App\Infra\Repositories\User;

use App\Domain\Entities\User as EntitiesUser;
use App\Domain\IRepository\IUserRepository;
use App\Domain\Enums\Profiles;
use App\Models\User;


class UserRepository implements IUserRepository
{

    public function getUserById(int $id): User
    {
        return User::find($id);
    }

    public function createUser(EntitiesUser $entitieUser, Profiles $profileType) : void
    {
        $user = User::create([
            'name' => $entitieUser->getName(),
            'email' => $entitieUser->getEmail(),
            'password' => $entitieUser->getPassword()
        ]);

        $user->profiles()->attach($profileType->value);
    }

    public function getUserByEmail(EntitiesUser $entitieUser): User
    {
        return User::where('email', $entitieUser->getEmail())
            ->with('profiles')
            ->first();
    }

    public function updateUser(EntitiesUser $entitieUser): void
    {
        $user = $this->getUserById($entitieUser->getId());

        $user->name = $entitieUser->getName();
        $user->email = $entitieUser->getEmail();
        
        $user->save();
    }
}