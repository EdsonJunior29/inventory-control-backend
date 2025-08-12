<?php

namespace App\Domain\IRepository;

use App\Domain\Entities\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface IProductRepository
{
    public function getAllProducts(int $pagination = 10): ?LengthAwarePaginator;

    public function getProductById(int $productId): ?Product;

    public function saveProduct(Product $product): ?Product;

    public function updateProduct(Product $product);

    public function productExists(int $productId): bool;

    public function deleteById(int $id): bool;

    public function existsBySerialNumber(string $serialNumber): bool;
}