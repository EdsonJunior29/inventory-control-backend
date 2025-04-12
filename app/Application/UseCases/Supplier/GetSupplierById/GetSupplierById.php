<?php

namespace App\Application\UseCases\Supplier\GetSupplierById;

use App\Application\DTOs\SupplierOutputDto;
use App\Domain\IRepository\ISupplierRepository;

class GetSupplierById
{
    protected $repo;

    public function __construct(ISupplierRepository $iSupplierRepository)
    {
        $this->repo = $iSupplierRepository;
    }

    public function execute(int $supplierId): ?SupplierOutputDto
    {
        $supplier = $this->repo->getSupplierById($supplierId);

        return SupplierOutputDto::fromEntity($supplier);
    }
}