<?php

namespace App\Domain\Services\SupplierServices;

use App\Domain\Exception\EmptyDataException;
use App\Domain\Exception\QueryExecutionException;
use App\Domain\IRepository\ISupplierRepository;
use App\Domain\UseCase\Supplier\GetSuppliers\GetAllSupplier;
use App\Infra\Supplier\SupplierRepository;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierService
{
    protected $supplierRepository;

    public function __construct(ISupplierRepository $getAllSupplierRepository)
    {
        $this->supplierRepository = $getAllSupplierRepository;
    }

    public function getAllSupliers() : LengthAwarePaginator
    {
        try {
            $getAllSuppliers = new GetAllSupplier(new SupplierRepository());
            $suppliers = $getAllSuppliers->execute();

            if($suppliers->isEmpty()) {
                throw new EmptyDataException();
            }
        } catch (QueryException $e) {
            throw new QueryExecutionException($e->getMessage(), $e->getCode());
        }

        return  $suppliers;
    }
}