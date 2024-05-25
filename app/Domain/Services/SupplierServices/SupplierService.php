<?php

namespace App\Domain\Services\SupplierServices;

use App\Domain\Exception\EmptyDataException;
use App\Domain\Exception\QueryExecutionException;
use App\Domain\IRepository\ISupplierRepository;
use App\Domain\UseCase\Supplier\GetAllSupplier\GetAllSupplier;
use App\Infra\User\SupplierRepository;
use Illuminate\Database\QueryException;

class SupplierService
{
    protected $supplierRepository;

    public function __construct(ISupplierRepository $getAllSupplierRepository)
    {
        $this->supplierRepository = $getAllSupplierRepository;
    }

    public function GetAllSupliers()
    {
        try {
            $getAllSuppliers = new GetAllSupplier(new SupplierRepository);
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