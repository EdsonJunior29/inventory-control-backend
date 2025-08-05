<?php

namespace App\Domain\IRepository;

use App\Application\DTOs\Suppliers\SupplierInputDto;
use App\Application\Resources\Suppliers\SupplierByIdResources;
use App\Domain\Entities\Supplier;

interface ISupplierRepository
{
    public function getAllSupplier(?int $perPage = 5);

    public function getSupplierById(int $supplierId): ?SupplierByIdResources;

    public function deleteSupplierById(int $supplierId);

    public function save(SupplierInputDto $supplierInputDto): Supplier;

    public function update(int $supplierId, array $data): bool;
}