<?php

namespace App\Domain\UseCase\User\CreateUser;

class CreateUserInputData
{
    public string $name;

    public string $email;

    public string $password;

    public function __construct(string $userName, string $userEmail, string $userPassword)
    {
        $this->name = $userName;
        $this->email = $userEmail;
        $this->password = $userPassword;
    }
}