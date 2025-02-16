<?php

namespace App\Application\UseCases\User\GetUserById;

class GetUserInputData
{
    public int $id;

    public function __construct(int $userId)
    {
        $this->id = $userId;
    }
}