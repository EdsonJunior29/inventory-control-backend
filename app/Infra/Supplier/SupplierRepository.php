<?php

namespace App\Infra\User;

use App\Domain\IRepository\ISupplierRepository;
use App\Models\Supplier;

class SupplierRepository implements ISupplierRepository
{
    public function getAllSupplier()
    {
        return Supplier::all()->paginate(10);
    }
}