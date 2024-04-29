<?php

namespace App\Domain\Repository;

use App\Domain\Repository\DefaultRepository;
use App\Models\User;

interface GetUserRepository extends DefaultRepository
{
    public function getUserByEmail(string $userEmail) : User;
}