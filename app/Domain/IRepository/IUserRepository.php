<?php

namespace App\Domain\IRepository;

use App\Enums\RoleType;
use App\Models\User;

interface IUserRepository
{
    public function createUser(string $name, string $email, string $password, RoleType $roleType) : User;
    public function getUserByEmail(string $userEmail) : ?User;
}