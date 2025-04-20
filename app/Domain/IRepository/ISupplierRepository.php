<?php

namespace App\Domain\IRepository;

use App\Application\DTOs\Suppliers\SupplierInputDto;
use App\Domain\Entities\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;

interface ISupplierRepository
{
    public function getAllSupplier() : LengthAwarePaginator;

    public function getSupplierById(int $supplierId): ?Supplier;

    public function deleteSupplierById(int $supplierId);

    public function save(SupplierInputDto $supplierInputDto): Supplier;

    public function update(int $supplierId, array $data): bool;
}