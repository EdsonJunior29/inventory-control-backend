<?php

namespace App\Domain\IRepository;

use App\Domain\Entities\User;
use App\Domain\Enums\Profiles;
use App\Models\User as ModelsUser;

interface IUserRepository
{
    public function getUserById(int $id): ModelsUser;
    public function createUser(User $user, Profiles $profileType) : void;
    public function getUserByEmail(User $user): ModelsUser;
    public function updateUser(User $user): void;
}