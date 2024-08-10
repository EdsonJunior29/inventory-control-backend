<?php

namespace App\Domain\IRepository;

use App\Domain\Entities\User;
use App\Enums\Profiles;

interface IUserRepository
{
    public function createUser(User $user, Profiles $profileType) : void;
    public function getUserByEmail(string $userEmail);
}