<?php

namespace App\Http\Controllers;

use App\Domain\Exception\EmptyDataException;
use App\Domain\Services\SupplierServices\SupplierService;
use App\Infra\Supplier\SupplierRepository;
use App\Traits\HttpResponses;
use Illuminate\Database\QueryException;

class SupplierController extends Controller
{
    use HttpResponses;

    public function getAllSuppliers()
    {
        $suppliersService = new SupplierService(new SupplierRepository());

        try {
            $suppliers = $suppliersService->GetAllSupliers();
        } catch (EmptyDataException $e) {
            return $this->success([], $e->getMessage());
        } catch (QueryException $qe) {
            return $this->error([], $qe->getMessage(), $qe->getCode());
        }

        return $suppliers;
    }
}
