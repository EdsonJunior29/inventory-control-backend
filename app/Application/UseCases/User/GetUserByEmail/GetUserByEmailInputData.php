<?php

namespace App\Application\UseCases\User\GetUserByEmail;

class GetUserByEmailInputData
{
    public string $email;

    public function __construct(string $userEmail)
    {
        $this->email = $userEmail;
    }
}