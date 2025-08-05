<?php

namespace App\Application\UseCases\Supplier\StoreSupplier;

use App\Application\DTOs\Suppliers\SupplierInputDto;
use App\Domain\IRepository\ISupplierRepository;

class StoreSupplier
{
    private $supplierRepository;

    public function __construct(ISupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    public function execute(SupplierInputDto $supplierInputDto)
    {
        return $this->supplierRepository->save($supplierInputDto);
    }
}