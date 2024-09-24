<?php

namespace App\Http\Controllers;

use App\Application\UseCases\Supplier\DeleteSupplierById\DeleteSupplierById;
use App\Application\UseCases\Supplier\GetSuppliers\GetAllSupplier;
use App\Application\UseCases\Supplier\GetSupplierById\GetSupplierById;
use App\Exceptions\InternalServerErrorException;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;

class SupplierController extends Controller
{
    use HttpResponses;

    private $getAllSuppliersUseCases;
    private $getSupplierByIdUseCases;
    private $deleteSupplierByIdUseCases;

    public function __construct(
        GetAllSupplier $getAllSuppliers, 
        GetSupplierById $getSupplierById,
        DeleteSupplierById $deleteSupplierById
    ) {
        $this->getAllSuppliersUseCases = $getAllSuppliers;
        $this->getSupplierByIdUseCases = $getSupplierById;
        $this->deleteSupplierByIdUseCases = $deleteSupplierById;
    }

    public function getAllSuppliers()
    {
        try {
            $suppliers = $this->getAllSuppliersUseCases->execute();

            if($suppliers->isEmpty()) {
                return $this->success([], 'No suppliers found', Response::HTTP_NOT_FOUND);
            }

        } catch (QueryException $qe) {
            return $this->error([], $qe->getMessage(), $qe->getCode());
        }

        return $suppliers;
    }

    public function getSupplierById($supplierId)
    {
        try {
            $supplier = $this->getSupplierByIdUseCases->execute($supplierId); 

            if( $supplier == null) {
                return $this->success([], 'No supplier found', Response::HTTP_NOT_FOUND);
            }

        } catch (QueryException $qe) {
            return $this->error([], 'Database query error: ' . $qe->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return $this->error([], 'An unexpected error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $supplier;
        
    }

    public function deleteSupplierById($supplierId)
    {
        try {
           $this->deleteSupplierByIdUseCases->execute($supplierId);
        } catch (InternalServerErrorException $e) {
            return $this->error([], 'An unexpected error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->success([], '', Response::HTTP_NO_CONTENT);
    }
}