<?php

namespace App\Infra\Repositories\Supplier;

use App\Application\Resources\Suppliers\SupplierResources;
use App\Domain\Entities\Supplier as EntitiesSupplier;
use App\Domain\Exceptions\SupplierNotFoundException;
use App\Domain\IRepository\ISupplierRepository;
use App\Infra\Helper\Pagination\PaginateResponse;
use App\Models\Supplier;

class SupplierRepository implements ISupplierRepository
{
    public function getAllSupplier(?int $perPage = 5)
    {
        $suppliers = Supplier::paginate($perPage);
        return PaginateResponse::format($suppliers, SupplierResources::class);
    }

    public function getSupplierById(int $supplierId): ?EntitiesSupplier
    {
        $model = Supplier::select(['id', 'name', 'email', 'phone', 'cnpj'])
            ->where('id', $supplierId)
            ->first();

        if(!$model) {
            throw new SupplierNotFoundException($supplierId);
        }

        return new EntitiesSupplier(
            $model->id,
            $model->name,
            $model->email,
            $model->phone,
            $model->cnpj
        );
    }

    public function deleteSupplierById(int $supplierId)
    {
        return Supplier::destroy($supplierId);
    }

    public function save($supplierInputDto): EntitiesSupplier
    {
        $model = Supplier::create([
            'name' => $supplierInputDto->name,
            'email' => $supplierInputDto->email,
            'phone' => $supplierInputDto->phone,
            'cnpj' => $supplierInputDto->cnpj
        ]);

        return new EntitiesSupplier(
            $model->id,
            $model->name,
            $model->email,
            $model->phone,
            $model->cnpj
        );
    }

    public function update(int $supplierId, array $data): bool
    {
        $model = Supplier::find($supplierId);

        if (!$model) {
            throw new SupplierNotFoundException($supplierId);
        }

        return $model->update($data);
    }
}