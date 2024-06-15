<?php

namespace App\Http\Controllers;

use App\Domain\Exception\EmptyDataException;
use App\Domain\Exception\QueryExecutionException;
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
        } catch (EmptyDataException $e) {
            return $this->success([], $e->getMessage());
        } catch (QueryException $qe) {
            return $this->error([], $qe->getMessage(), $qe->getCode());
        }

        return $suppliers;
    }

    public function getSupplierById($supplierId)
    {
        try {
            $supplier = $this->suppliersService->getSupplierById($supplierId); 
        } catch (EmptyDataException $e) {
            return $this->success([], $e->getMessage());
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
        } catch (QueryExecutionException $queryEx) {
            return $this->success([], $queryEx->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return $this->error([], 'An unexpected error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->success([], 'Supplier deleted successfully', Response::HTTP_NO_CONTENT);
    }
}
