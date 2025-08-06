<?php

namespace App\Infra\Repositories\Product;

use App\Domain\Entities\Product as EntitiesProduct;
use App\Domain\IRepository\IProductRepository;
use App\Domain\ValueObjects\Category;
use App\Domain\ValueObjects\Status;
use App\Models\Product;
use \DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements IProductRepository
{
    public function getAllProducts(int $pagination = 10): ?LengthAwarePaginator
    {
        $paginated = Product::with(['category', 'status'])->paginate($pagination);

        if ($paginated->isNotEmpty()) {
            $paginated->getCollection()->transform(function ($model) {
                    return new EntitiesProduct(
                        id: $model->id,
                        name: $model->name,
                        brand : $model->brand,
                        category: new Category($model->category->name),
                        description: $model->description,
                        quantityInStock: $model->quantity_in_stock,
                        serialNumber: $model->serial_number,
                        dateOfAcquisition: new DateTime($model->date_of_acquisition),
                        status: new Status($model->status->name),
                    );
            });
        }

        return $paginated;
    }
}