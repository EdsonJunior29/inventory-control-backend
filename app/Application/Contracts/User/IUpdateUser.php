<?php

namespace App\Application\Contracts\User;

use App\Application\UseCases\User\UpdateUser\UpdateUserInputData;

interface IUpdateUser
{
    public function execute(UpdateUserInputData $updateUserInputData);
}