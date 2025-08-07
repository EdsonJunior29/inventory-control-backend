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
                return $this->formatForEntitiesProduct($model);
            });
        }

        return $paginated;
    }

    public function getProductById(int $productId): ?EntitiesProduct
    {
        $product = Product::with(['category', 'status'])
            ->find($productId);

        if ($product) {
            return $this->formatForEntitiesProduct($product);
        }

        return $product;
    }

    private function formatForEntitiesProduct(Product $product)
    {
        return new EntitiesProduct(
            id: $product->id,
            name: $product->name,
            brand : $product->brand,
            category: new Category($product->category->name),
            description: $product->description,
            quantityInStock: $product->quantity_in_stock,
            serialNumber: $product->serial_number,
            dateOfAcquisition: new DateTime($product->date_of_acquisition),
            status: new Status($product->status->name),
        );
    }
}