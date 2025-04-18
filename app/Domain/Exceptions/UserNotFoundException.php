<?php

namespace App\Domain\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct(int $userId)
    {
        parent::__construct("User with ID {$userId} not found");
    }
}