<?php

namespace App\Http\Controllers;

use App\Domain\Exception\InternalServerErrorException;
use App\Domain\Services\SupplierServices\SupplierService;
use App\Infra\Supplier\SupplierRepository;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;

class SupplierController extends Controller
{
    use HttpResponses;

    protected $supplierRepository;
    protected $suppliersService;

    public function __construct(SupplierRepository $supplierRepository) {
        $this->supplierRepository = $supplierRepository;
        $this->suppliersService = new SupplierService($this->supplierRepository);
    }

    public function getAllSuppliers()
    {
        try {
            $suppliers = $this->suppliersService->getAllSupliers();

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
            $supplier = $this->suppliersService->getSupplierById($supplierId); 

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
           $this->suppliersService->deleteSupplierById($supplierId);
        } catch (InternalServerErrorException $e) {
            return $this->error([], 'An unexpected error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->success([], '', Response::HTTP_NO_CONTENT);
    }
}
