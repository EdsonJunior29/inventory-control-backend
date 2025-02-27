<?php

namespace App\Infra\Repositories\Supplier;

use App\Domain\IRepository\ISupplierRepository;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierRepository implements ISupplierRepository
{
    public function getAllSupplier() : LengthAwarePaginator
    {
        return Supplier::select(['id', 'name'])->paginate(5);
    }

    public function getSupplierById(int $supplierId)
    {
        return Supplier::select(['id', 'name', 'email', 'phone'])
            ->where('id', $supplierId)
            ->first();
    }

    public function deleteSupplierById(int $supplierId)
    {
        return Supplier::destroy($supplierId);
    }
}