<?php

namespace App\Application\UseCases\Products\StoreProducts;

use App\Application\DTOs\Products\ProductInputDto;
use App\Domain\Entities\Product;
use App\Domain\IRepository\ICategoryRepository;
use App\Domain\IRepository\IProductRepository;
use App\Domain\IRepository\IStatusRepository;
use App\Domain\ValueObjects\Category;
use App\Domain\ValueObjects\Status;

class StoreProduct
{
    private $productRepository;
    private $categoryRepository;
    private $statusRepository;

    private array $categoryCache = [];
    private array $statusCache = [];

    public function __construct(
        IProductRepository $productRepository,
        ICategoryRepository $categoryRepository,
        IStatusRepository $statusRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->statusRepository = $statusRepository;
    }

    public function execute(ProductInputDto $productInputDto)
    {
        $categoryVO = $this->getCategoryIndformation($productInputDto->categoryId);
        $statusVO = $this->getStatusIndformation($productInputDto->statusId);
        $serialNumber = $this->createSerialNumber();

        $entitieProduct = Product::createWithAutoSerial(
            name: $productInputDto->name,
            brand: $productInputDto->brand,
            category: $categoryVO,
            description: $productInputDto->description,
            quantityInStock: $productInputDto->quantityInStock,
            serialNumber: $serialNumber,
            dateOfAcquisition: $productInputDto->dateOfAcquisition,
            status: $statusVO
        );

        return $this->productRepository->saveProduct($entitieProduct);
    }

    private function createSerialNumber()
    {
        return Product::generateSerialNumber();
    }

    private function getCategoryIndformation(int $categoryId): Category
    {
        if (!isset($this->categoryCache[$categoryId])) {
            $category = $this->categoryRepository->getCategoryById($categoryId);
            $this->categoryCache[$categoryId] = new Category($category->id, $category->name);
        }
        
        return $this->categoryCache[$categoryId];
    }

    private function getStatusIndformation(int $statusId): Status
    {
        if (!isset($this->statusCache[$statusId])) {
            $status = $this->statusRepository->getStatusById($statusId);
            $this->statusCache[$statusId] = new Status($status->id, $status->name);
        }
        return $this->statusCache[$statusId];
    }
}