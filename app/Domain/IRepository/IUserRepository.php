<?php

namespace App\Domain\IRepository;

use App\Domain\Entities\User;
use App\Enums\Profiles;
use App\Models\User as ModelsUser;

interface IUserRepository
{
    public function createUser(User $user, Profiles $profileType) : void;
    public function getUserByEmail(User $user): ModelsUser;
}