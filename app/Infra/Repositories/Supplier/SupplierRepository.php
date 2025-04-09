<?php

namespace App\Infra\Repositories\Supplier;

use App\Domain\Entities\Supplier as EntitiesSupplier;
use App\Domain\Exceptions\SupplierNotFoundException;
use App\Domain\IRepository\ISupplierRepository;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierRepository implements ISupplierRepository
{
    public function getAllSupplier() : LengthAwarePaginator
    {
        return Supplier::select(['id', 'name'])->paginate(5);
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
}