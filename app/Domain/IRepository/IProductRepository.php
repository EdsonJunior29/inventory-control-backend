<?php

namespace App\Domain\IRepository;

use Illuminate\Pagination\LengthAwarePaginator;

interface IProductRepository
{
    public function getAllProducts(int $pagination = 10): ?LengthAwarePaginator;
}