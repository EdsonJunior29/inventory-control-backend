<?php

namespace App\Domain\UseCase\User\GetUserByEmail;

class GetUserByEmailInputData
{
    public string $email;

    public function __construct(string $userEmail)
    {
        $this->email = $userEmail;
    }
}