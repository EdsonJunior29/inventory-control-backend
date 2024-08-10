<?php

namespace App\Application\UseCases\Auth\AuthUser;

class AuthUserInputData
{
    public string $email;

    public string $password;

    public function __construct(string $userEmail, string $userPassword)
    {
        $this->email = $userEmail;
        $this->password = $userPassword;
    }
}