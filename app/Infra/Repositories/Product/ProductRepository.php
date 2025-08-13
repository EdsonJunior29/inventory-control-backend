<?php

namespace App\Infra\Repositories\Product;

use App\Domain\Entities\Product as EntitiesProduct;
use App\Domain\IRepository\ICategoryRepository;
use App\Domain\IRepository\IProductRepository;
use App\Domain\IRepository\IStatusRepository;
use App\Domain\ValueObjects\Category;
use App\Domain\ValueObjects\Status;
use App\Models\Product;
use \DateTime;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements IProductRepository
{
    public function __construct(
        private ICategoryRepository $categoryRepository,
        private IStatusRepository $statusRepository
    ) {}

    public function getAllProducts(int $pagination = 10): ?LengthAwarePaginator
    {
        $products = Product::with([
            'category:id,name',
            'status:id,name'
        ])->select([
            'id',
            'name',
            'brand',
            'category_id',
            'status_id',
            'quantity_in_stock',
            'serial_number',
            'date_of_acquisition',
            'description'
        ])->paginate($pagination);

        if ( $products->isNotEmpty()) {
            $transformed = $products->getCollection()->transform(function ($model) {
            return $this->formatForEntitiesProduct($model);
            });

            $products->setCollection($transformed);
        }

        return $products;
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

    public function saveProduct(EntitiesProduct $product): ?EntitiesProduct
    {
        $productCreated = Product::create([
            'name' => $product->getName(),
            'brand' => $product->getBrand(),
            'category_id' => $product->getCategory()->getId(),
            'description' => $product->getDescription() ?? "",
            'quantity_in_stock' => $product->getQuantityInStock(),
            'serial_number' => $product->getSerialNumber(),
            'date_of_acquisition' => $product->getDateOfAcquisition(),
            'status_id' => $product->getStatus()->getId()
        ]);

        $productCreated->load(['category', 'status']); // eager loading

        return new EntitiesProduct(
            $productCreated->id,
            $productCreated->name,
            $productCreated->brand,
            new Category($productCreated->category->id, $productCreated->category->name),
            $productCreated->description,
            $productCreated->quantity_in_stock,
            $productCreated->serial_number,
            $productCreated->date_of_acquisition,
            new Status($productCreated->status->id, $productCreated->status->name)
        );
    }

    public function updateProduct(EntitiesProduct $productEntity)
    {
        $product = Product::find($productEntity->getId());

        $product->name = $productEntity->getName();
        $product->brand = $productEntity->getBrand();
        $product->category_id = $productEntity->getCategory()?->getId();
        $product->description = $productEntity->getDescription();
        $product->quantity_in_stock = $productEntity->getQuantityInStock();
        $product->serial_number = $productEntity->getSerialNumber();
        $product->date_of_acquisition = $productEntity->getDateOfAcquisition()->format('Y-m-d H:i:s');
        $product->status_id = $productEntity->getStatus()?->getId();

        $product->save();
    }

    public function productExists(int $productId): bool
    {
        return Product::where('id', $productId)->exists();
    }

    public function deleteById(int $id): bool
    {
        return Product::destroy($id) > 0;
    }

    public function existsBySerialNumber(string $serialNumber): bool
    {
        return Product::where('serial_number', $serialNumber)->exists();
    }

    private function formatForEntitiesProduct(Product $product)
    {
        return new EntitiesProduct(
            id: $product->id,
            name: $product->name,
            brand : $product->brand,
            category: new Category(
                $product->category->id,
                $product->category->name
            ),
            description: $product->description,
            quantityInStock: $product->quantity_in_stock,
            serialNumber: $product->serial_number,
            dateOfAcquisition: new DateTime($product->date_of_acquisition),
            status: new Status(
                $product->status->id, 
                $product->status->name
            ),
        );
    }
}