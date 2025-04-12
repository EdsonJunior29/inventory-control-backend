<?php

namespace App\Application\UseCases\Supplier\DeleteSupplierById;

use App\Domain\IRepository\ISupplierRepository;

class DeleteSupplierById
{
    protected $repo;

    public function __construct(ISupplierRepository $iSupplierRepository)
    {
        $this->repo = $iSupplierRepository;
    }

    public function execute(int $supplierId)
    {
        $supplierDatabase = $this->querySupplierData($supplierId);
        
        if($supplierDatabase === null) {
            return $supplierDatabase;
        }

        $this->repo->deleteSupplierById($supplierDatabase->getId());
    }

    public function querySupplierData(int $supplierId)
    {
        return $this->repo->getSupplierById($supplierId);
    }

}