<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class Status
{
    private string $name;

    public function __construct(string $statusName)
    {
        if (strlen($statusName) < 1) {
            throw new InvalidArgumentException("Category name must be at least 1 character.");
        }

        $this->name = $statusName;
    }

    public function getName(): string
    {
        return $this->name;
    }
}