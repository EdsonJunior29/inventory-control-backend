<?php

namespace App\Application\UseCases\Products\GetProducts;

use App\Domain\IRepository\IProductRepository;

class GetAllProducts
{
    protected $repo;

    public function __construct(IProductRepository $iProductRepository)
    {
        $this->repo = $iProductRepository;
    }

    public function execute()
    {
        return $this->repo->getAllProducts();
    }
}