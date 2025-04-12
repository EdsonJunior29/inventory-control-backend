<?php

namespace App\Api\Http\Controllers;

use App\Application\UseCases\Supplier\DeleteSupplierById\DeleteSupplierById;
use App\Application\UseCases\Supplier\GetSuppliers\GetAllSupplier;
use App\Application\UseCases\Supplier\GetSupplierById\GetSupplierById;
use App\Domain\Exceptions\InternalServerErrorException;
use App\Api\Traits\HttpResponses;
use App\Domain\Exceptions\SupplierNotFoundException;
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
            $suppliersDtos = $this->getAllSuppliersUseCases->execute();

            if(empty($suppliersDtos)) {
                return $this->success([], 'No suppliers found', Response::HTTP_NOT_FOUND);
            }

        } catch (QueryException $qe) {
            return $this->error([], $qe->getMessage(), $qe->getCode());
        }

        return $this->success($suppliersDtos, 'Suppliers retrieved successfully', Response::HTTP_OK);
    }

    public function getSupplierById($supplierId)
    {
        try {
            $supplierDto = $this->getSupplierByIdUseCases->execute((int) $supplierId);
    
            return $this->success($supplierDto);
    
        } catch (SupplierNotFoundException $e) {
            return $this->error([], $e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (QueryException $qe) {
            return $this->error([], 'Database query error: ' . $qe->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return $this->error([], 'An unexpected error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
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