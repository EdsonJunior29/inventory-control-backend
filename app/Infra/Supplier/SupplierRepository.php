<?php

namespace App\Infra\Supplier;

use App\Domain\IRepository\ISupplierRepository;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierRepository implements ISupplierRepository
{
    public function getAllSupplier() : LengthAwarePaginator
    {
        return Supplier::select(['id', 'name'])->paginate(5);
    }
}