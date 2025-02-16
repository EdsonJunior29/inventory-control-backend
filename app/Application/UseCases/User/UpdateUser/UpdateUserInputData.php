<?php

namespace App\Application\UseCases\User\UpdateUser;

class UpdateUserInputData
{
    public int $id;
    public string $name;
    public string $email;

    public function __construct(int $id, string $name, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }
}