<?php

namespace App\Domain\UseCase\Supplier\DeleteSupplierById;

use App\Domain\Exception\EmptyDataException;
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
            throw new EmptyDataException('supplier not found');
        }

        $this->repo->deleteSupplierById($supplierDatabase->id);
    }

    public function querySupplierData(int $supplierId)
    {
        return $this->repo->getSupplierById($supplierId);
    }

}