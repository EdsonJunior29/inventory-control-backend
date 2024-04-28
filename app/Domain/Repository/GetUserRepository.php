<?php

namespace App\Domain\Repository;

use App\Domain\Repository\Repository;
use App\Models\User;

interface GetUserRepository extends Repository
{
    public function getUserByEmail(string $userEmail) : User;
}