<?php

namespace App\Application\UseCases\Products\UpdateProductById;

use App\Application\DTOs\Products\ProductInputDto;
use App\Domain\IRepository\ICategoryRepository;
use App\Domain\IRepository\IProductRepository;
use App\Domain\IRepository\IStatusRepository;
use App\Domain\ValueObjects\Category;
use App\Domain\ValueObjects\Status;

class UpdateProductById
{
    private $repo;
    private $categoryRepository;
    private $statusRepository;


    public function __construct(
        IProductRepository $iProductRepository,
        ICategoryRepository $iCategoryRepository,
        IStatusRepository $iStatusRepository
    ) {
        $this->repo = $iProductRepository;
        $this->categoryRepository = $iCategoryRepository;
        $this->statusRepository = $iStatusRepository; 
    }

    public function execute(int $productId, ProductInputDto $productInputDto)
    {
        $entityProduct = $this->repo->getProductById($productId);

        if (!$entityProduct) {
          throw new \Exception("Product not found");
        }

        if (!$this->isSameInformation($productInputDto->categoryId, $entityProduct->getCategory()?->getId())) {
            $category = $this->categoryRepository->getCategoryById($productInputDto->categoryId);
            $categoryVO = new Category(
                $category->id,
                $category->name 
            );
            $entityProduct->setCategory($categoryVO);
        }

        if (!$this->isSameInformation($productInputDto->statusId, $entityProduct->getStatus()?->getId())) {
            $status = $this->statusRepository->getStatusById($productInputDto->statusId);
            $statusVO = new Status(
               $status->id,
               $status->name
            );
            $entityProduct->setStatus($statusVO);
        }

        $entityProduct->setName($productInputDto->name);
        $entityProduct->setBrand($productInputDto->brand);
        $entityProduct->setDescription($productInputDto->description);
        $entityProduct->setQuantityInStock($productInputDto->quantityInStock);
        $entityProduct->setDateOfAcquisition($productInputDto->dateOfAcquisition);

        $this->repo->updateProduct($entityProduct);

        return $entityProduct;
    }

    private function isSameInformation(?int $idDto, ?int $idEntity): bool
    {
        return $idDto === $idEntity;
    }
}