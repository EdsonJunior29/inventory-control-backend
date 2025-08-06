<?php

namespace App\Api\Http\Controllers;

use App\Api\Http\Requests\StoreSupplierRequest;
use App\Api\Http\Requests\UpdateSupplierRequest;
use App\Application\UseCases\Supplier\DeleteSupplierById\DeleteSupplierById;
use App\Application\UseCases\Supplier\GetSuppliers\GetAllSupplier;
use App\Application\UseCases\Supplier\GetSupplierById\GetSupplierById;
use App\Domain\Exceptions\InternalServerErrorException;
use App\Api\Traits\HttpResponses;
use App\Application\DTOs\Suppliers\SupplierInputDto;
use App\Application\UseCases\Supplier\StoreSupplier\StoreSupplier;
use App\Application\UseCases\Supplier\UpdateSupplier\UpdateSupplier;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;

class SupplierController extends Controller
{
    use HttpResponses;

    private $getAllSuppliersUseCases;
    private $getSupplierByIdUseCases;
    private $deleteSupplierByIdUseCases;
    private $storeSupplierUseCases;
    private $updateSupplierUseCases;

    public function __construct(
        GetAllSupplier $getAllSuppliers, 
        GetSupplierById $getSupplierById,
        DeleteSupplierById $deleteSupplierById,
        StoreSupplier $storeSupplier,
        UpdateSupplier $updateSupplier
    ) {
        $this->getAllSuppliersUseCases = $getAllSuppliers;
        $this->getSupplierByIdUseCases = $getSupplierById;
        $this->deleteSupplierByIdUseCases = $deleteSupplierById;
        $this->storeSupplierUseCases = $storeSupplier;
        $this->updateSupplierUseCases = $updateSupplier;
    }

    public function getAllSuppliers()
    {
        try {
            $suppliers = $this->getAllSuppliersUseCases->execute();

            if(empty($suppliers)) {
                return $this->success(
                    [],
                    'No suppliers found',
                    Response::HTTP_NOT_FOUND
                );
            }

        } catch (QueryException $qe) {
            return $this->error([], $qe->getMessage(), $qe->getCode());
        }

        return $this->success($suppliers, 'Suppliers retrieved successfully', Response::HTTP_OK);
    }

    public function getSupplierById($supplierId)
    {
        try {
            $supplier = $this->getSupplierByIdUseCases->execute((int) $supplierId);

            if (!$supplier) {
                return $this->success(
                    $supplier,
                    'No supplier found with this ID', 
                    Response::HTTP_NOT_FOUND
                );
            }

            return $this->success($supplier);
    
        } catch (QueryException $qe) {
            return $this->error(
                [], 
                'Database query error: ' . $qe->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch (Exception $e) {
            return $this->error(
                [],
                'An unexpected error occurred: ' . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
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

    public function store(StoreSupplierRequest $request)
    {
        $supplierInputDto = new SupplierInputDto(
            name: $request->name,
            email: $request->email,
            phone: $request->phone,
            cnpj: $request->cnpj,
        );

        try {
            $supplierDtoOutput = $this->storeSupplierUseCases->execute($supplierInputDto);
            
            return $this->success(
                $supplierDtoOutput,
                'Supplier created successfully',
                Response::HTTP_CREATED
            );
            
        } catch (QueryException $qe) {
            return $this->error(
                [], 
                'Database query error: ' . $qe->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );

        } catch (\Exception $th) {
            return $this->error(
                [],
                'Internal server error: ' . $th->getMessage(), 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function update($supplierId, UpdateSupplierRequest $request)
    {
        try {
            $updated = $this->updateSupplierUseCases->execute((int) $supplierId, $request->all());
            
            return $this->success(
                $updated,
                'Supplier updated successfully',
                Response::HTTP_OK
            );
            
        } catch (QueryException $qe) {
            return $this->error(
                [], 
                'Database query error: ' . $qe->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );

        } catch (\Exception $th) {
            return $this->error(
                [],
                'Internal server error: ' . $th->getMessage(), 
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        
    }
}