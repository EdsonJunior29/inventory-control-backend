<?php

namespace App\Domain\UseCase\User\CreateUser;

use App\Enums\RoleType;

class CreateUserInputData
{
    public string $name;

    public string $email;

    public string $password;

    public RoleType $roleType;

    public function __construct(string $userName, string $userEmail, string $userPassword, RoleType $roleType)
    {
        $this->name = $userName;
        $this->email = $userEmail;
        $this->password = $userPassword;
        $this->roleType = $roleType;
    }
}