<?php

namespace App\Application\UseCases\Supplier\GetSuppliers;

use App\Domain\IRepository\ISupplierRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAllSupplier
{
    protected $repo;

    public function __construct(ISupplierRepository $iSupplierRepository)
    {
        $this->repo = $iSupplierRepository;
    }

    public function execute(): LengthAwarePaginator
    {
        return $this->repo->getAllSupplier();
    }
}