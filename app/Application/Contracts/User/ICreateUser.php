<?php

namespace App\Application\Contracts\User;

use App\Application\UseCases\User\CreateUser\CreateUserInputData;
use App\Models\User;

interface ICreateUser
{
    public function execute(CreateUserInputData $inputData): User;
}