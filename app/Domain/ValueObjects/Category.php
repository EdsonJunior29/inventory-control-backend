<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class Category
{
    private int $id;
    private string $name;

    public function __construct(int $id, string $categoryName)
    {
        $this->id = $id;

        if (strlen($categoryName) < 1) {
            throw new InvalidArgumentException("Category name must be at least 1 character.");
        }

        $this->name = $categoryName;
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