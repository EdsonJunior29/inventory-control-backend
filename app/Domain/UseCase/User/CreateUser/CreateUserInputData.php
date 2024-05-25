<?php

namespace App\Domain\UseCase\User\CreateUser;

use App\Enums\Profiles;

class CreateUserInputData
{
    public string $name;

    public string $email;

    public string $password;

    public Profiles $profileType;

    public function __construct(string $userName, string $userEmail, string $userPassword, Profiles $profileType)
    {
        $this->name = $userName;
        $this->email = $userEmail;
        $this->password = $userPassword;
        $this->profileType = $profileType;
    }
}