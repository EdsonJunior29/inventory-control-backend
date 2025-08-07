<?php

namespace App\Domain\IRepository;

use App\Domain\Entities\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface IProductRepository
{
    public function getAllProducts(int $pagination = 10): ?LengthAwarePaginator;

    public function getProductById(int $productId): ?Product;
}