<?php

namespace App\Domain\IRepository;

use App\Models\User;

interface IUserRepository
{
    public function createUser(string $name, string $email, string $password) : User;
    public function getUserByEmail(string $userEmail) : User;
}