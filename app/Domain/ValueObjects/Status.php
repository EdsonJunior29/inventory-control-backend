<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class Status
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $statusName)
    {
        $this->id = $id;
        
        if (strlen($statusName) < 1) {
            throw new InvalidArgumentException("Category name must be at least 1 character.");
        }

        $this->name = $statusName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}