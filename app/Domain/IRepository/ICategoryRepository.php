<?php

namespace App\Domain\IRepository;

use App\Models\Category;

interface ICategoryRepository
{
    public function getCategoryById(int $categoryId): ?Category;
}