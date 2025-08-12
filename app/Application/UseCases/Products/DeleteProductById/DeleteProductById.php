<?php

namespace App\Application\UseCases\Products\DeleteProductById;

use App\Domain\IRepository\IProductRepository;
use Exception;
use Illuminate\Http\Response;

class DeleteProductById
{
    private $repo;

    public function __construct(IProductRepository $repo)
    {
        $this->repo = $repo;
    }

    public function execute(int $productId): bool
    {
        if (!$this->repo->productExists($productId)) {
            throw new Exception(
                "Product not found.",
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->repo->deleteById($productId);
    }
}