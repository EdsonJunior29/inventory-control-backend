<?php

namespace App\Http\Controllers;

use App\Domain\Exception\EmptyDataException;
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

    public function __construct(SupplierRepository $supplierRepository) {
        $this->supplierRepository = $supplierRepository;
    }

    public function getAllSuppliers()
    {
        $suppliersService = new SupplierService($this->supplierRepository);

        try {
            $suppliers = $suppliersService->getAllSupliers();
        } catch (EmptyDataException $e) {
            return $this->success([], $e->getMessage());
        } catch (QueryException $qe) {
            return $this->error([], $qe->getMessage(), $qe->getCode());
        }

        return $suppliers;
    }

    public function getSupplierById($supplierId)
    {
        $suppliersService = new SupplierService($this->supplierRepository);

        try {
            $supplier = $suppliersService->getSupplierById($supplierId); 
        } catch (EmptyDataException $e) {
            return $this->success([], $e->getMessage());
        } catch (QueryException $qe) {
            return $this->error([], 'Database query error: ' . $qe->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return $this->error([], 'An unexpected error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $supplier;
        
    }
}
