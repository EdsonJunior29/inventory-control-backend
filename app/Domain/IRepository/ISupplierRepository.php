<?php

namespace App\Domain\IRepository;

use Illuminate\Pagination\LengthAwarePaginator;

interface ISupplierRepository
{
    public function getAllSupplier() : LengthAwarePaginator;

    public function getSupplierById(int $supplierId);
}