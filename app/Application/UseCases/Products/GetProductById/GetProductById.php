<?php

namespace App\Application\UseCases\Products\GetProductById;

use App\Domain\IRepository\IProductRepository;

class GetProductById
{
    protected $repo;

    public function __construct(IProductRepository $iProductRepository)
    {
        $this->repo = $iProductRepository;
    }

    public function execute(int $productId)
    {
        return $this->repo->getProductById($productId);
    }
}