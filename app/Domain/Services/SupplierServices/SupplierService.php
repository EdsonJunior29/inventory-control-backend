<?php

namespace App\Domain\Services\SupplierServices;

use App\Domain\Exception\InternalServerErrorException;
use App\Domain\Exception\QueryExecutionException;
use App\Domain\IRepository\ISupplierRepository;
use App\Domain\UseCase\Supplier\DeleteSupplierById\DeleteSupplierById;
use App\Domain\UseCase\Supplier\GetSupplierById\GetSupplierById;
use App\Domain\UseCase\Supplier\GetSuppliers\GetAllSupplier;
use App\Infra\Supplier\SupplierRepository;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

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
            
        } catch (Throwable $e) {
            throw new QueryExecutionException($e->getMessage(), $e->getCode());
        }

        return  $suppliers;
    }

    public function getSupplierById(int $supplierId)
    {
        try {
            $getSupplierById = new GetSupplierById(new SupplierRepository());
            $supplier = $getSupplierById->execute($supplierId);

        } catch (QueryException $qe) {
            throw new QueryExecutionException('Database query error: ' . $qe->getMessage());
        }

        return $supplier;
    }

    public function deleteSupplierById(int $supplierId)
    {
        try {
            $deleteSupplierById = new DeleteSupplierById(new SupplierRepository());
            $supplier = $deleteSupplierById->execute($supplierId);
        } catch (QueryException $th) {
            throw new InternalServerErrorException($th->getMessage(), $th->getCode());
        }
       
        return $supplier;
    }
}