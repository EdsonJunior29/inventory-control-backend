<?php

namespace App\Domain\UseCase\Supplier\GetSupplierById;

use App\Domain\IRepository\ISupplierRepository;

class GetSupplierById
{
    protected $repo;

    public function __construct(ISupplierRepository $iSupplierRepository)
    {
        $this->repo = $iSupplierRepository;
    }

    public function execute(int $supplierId)
    {
        return $this->repo->getSupplierById($supplierId);
    }

}