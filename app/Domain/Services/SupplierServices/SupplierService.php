<?php

namespace App\Domain\Services\SupplierServices;

use App\Domain\Exception\EmptyDataException;
use App\Domain\Exception\QueryExecutionException;
use App\Domain\IRepository\ISupplierRepository;
use App\Domain\UseCase\Supplier\DeleteSupplierById\DeleteSupplierById;
use App\Domain\UseCase\Supplier\GetSupplierById\GetSupplierById;
use App\Domain\UseCase\Supplier\GetSuppliers\GetAllSupplier;
use App\Infra\Supplier\SupplierRepository;
use Exception;
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

    public function getSupplierById(int $supplierId)
    {
        try {
            $getSupplierById = new GetSupplierById(new SupplierRepository());
            $supplier = $getSupplierById->execute($supplierId);

            if( $supplier == null) {
                throw new EmptyDataException();
            }
        } catch (QueryException $qe) {
            throw new QueryExecutionException('Database query error: ' . $qe->getMessage());
        }

        return $supplier;
    }

    public function deleteSupplierById(int $supplierId)
    {
        try {
            $deleteSupplierById = new DeleteSupplierById(new SupplierRepository());
            $deleteSupplierById->execute($supplierId);
        } catch (EmptyDataException $qe) {
            throw new QueryExecutionException($qe->getMessage());
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}