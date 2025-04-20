<?php

namespace App\Infra\Repositories\Supplier;

use App\Application\DTOs\Suppliers\SupplierOutputDto;
use App\Domain\Entities\Supplier as EntitiesSupplier;
use App\Domain\Exceptions\SupplierNotFoundException;
use App\Domain\IRepository\ISupplierRepository;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;

class SupplierRepository implements ISupplierRepository
{
    public function getAllSupplier() : LengthAwarePaginator
    {
        $paginator = Supplier::select(['id', 'name'])->paginate(5);

        $entities = $paginator->getCollection()->map(function ($model) {
            return new EntitiesSupplier(
                $model->id,
                $model->name
            );
        });

        $supplierEntitiesDtos = $entities->map(function ($supplier) {
            return SupplierOutputDto::fromEntity($supplier);
        });

        return new LengthAwarePaginator(
            $supplierEntitiesDtos,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            [
                'path' => Request::url(),
                'query' => Request::query()
            ]
        );

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