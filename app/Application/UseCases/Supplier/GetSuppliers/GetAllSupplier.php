<?php

namespace App\Application\UseCases\Supplier\GetSuppliers;

use App\Domain\IRepository\ISupplierRepository;

class GetAllSupplier
{
    protected $repo;

    public function __construct(ISupplierRepository $iSupplierRepository)
    {
        $this->repo = $iSupplierRepository;
    }

    public function execute()
    {
        return $this->repo->getAllSupplier();
    }

}