<?php

namespace App\Infra\Repositories\Category;

use App\Domain\IRepository\ICategoryRepository;
use App\Models\Category;

class CategoryRepository implements ICategoryRepository
{
    public function getCategoryById(int $categoryId): ?Category
    {
        return Category::select(['id', 'name'])->find($categoryId);
    }
}