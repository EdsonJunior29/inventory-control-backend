<?php

namespace App\Domain\IRepository;

use App\Enums\Profiles;
use App\Models\User;

interface IUserRepository
{
    public function createUser(string $name, string $email, string $password, Profiles $profileType) : User;
    public function getUserByEmail(string $userEmail) : ?User;
}