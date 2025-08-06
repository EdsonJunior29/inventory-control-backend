<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

class Category
{
    private string $name;

    public function __construct(string $categoryName)
    {
        if (strlen($categoryName) < 1) {
            throw new InvalidArgumentException("Category name must be at least 1 character.");
        }

        $this->name = $categoryName;
    }

    public function getName(): string
    {
        return $this->name;
    }
}