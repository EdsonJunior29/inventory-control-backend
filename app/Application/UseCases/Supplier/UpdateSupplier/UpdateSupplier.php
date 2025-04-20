<?php

namespace App\Application\UseCases\Supplier\UpdateSupplier;

use App\Domain\IRepository\ISupplierRepository;

class UpdateSupplier
{
    private $supplierRepository;

    public function __construct(ISupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    public function execute(int $supplierId, array $data): bool
    {
        return $this->supplierRepository->update($supplierId, $data);
    }
}