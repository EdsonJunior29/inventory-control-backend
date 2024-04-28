<?php

namespace App\Infra\User;

use App\Models\User;
use App\Domain\Repository\GetUserRepository;

class GetUser implements GetUserRepository
{
    public function getUserByEmail(string $userEmail) : User
    {
        return User::where('email', $userEmail)->first();
    }
}
