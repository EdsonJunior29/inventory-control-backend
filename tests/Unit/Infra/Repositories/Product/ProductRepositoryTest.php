<?php

namespace Tests\Unit\Infra\Repositories\Product;

use App\Application\DTOs\Products\ProductInputDto;
use App\Domain\Entities\Product as EntitiesProduct;
use App\Domain\IRepository\ICategoryRepository;
use App\Domain\IRepository\IStatusRepository;
use App\Domain\ValueObjects\Category as ValueObjectsCategory;
use App\Domain\ValueObjects\Status as ValueObjectsStatus;
use App\Infra\Repositories\Product\ProductRepository;
use App\Models\Category;
use App\Models\Product;
use App\Models\Status;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\Attributes\TestWith;
use DateTime;

# php artisan test --filter=ProductRepositoryTest
class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $iCategoryRepository = Mockery::mock(ICategoryRepository::class);
        $iStatusRepository = Mockery::mock(IStatusRepository::class);

        $this->repository = new ProductRepository(
            $iCategoryRepository,
            $iStatusRepository
        );
    }

    # php artisan test --filter=ProductRepositoryTest::test_returns_paginated_products_with_entities
    public function test_returns_paginated_products_with_entities()
    {
        $category = Category::factory()->create(['name' => 'Eletronic']);
        $status = Status::factory()->create(['name' => 'Active']);

        Product::factory()->count(3)->create([
            'category_id' => $category->id,
            'status_id' => $status->id,
        ]);

        $result = $this->repository->getAllProducts();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(3, $result->items());
        $this->assertInstanceOf(EntitiesProduct::class, $result->items()[0]);
    }

    # php artisan test --filter=ProductRepositoryTest::test_returns_empty_paginated_result_when_no_products_exist
    public function test_returns_empty_paginated_result_when_no_products_exist()
    {
        $result = $this->repository->getAllProducts();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(0, $result->items());
    }

    #[TestWith([1])]
    # php artisan test --filter=ProductRepositoryTest::test_returns_product_by_id_with_entities
    public function test_returns_product_by_id_with_entities(int $productId)
    {
        $category = Category::factory()->create(['name' => 'Eletronic']);
        $status = Status::factory()->create(['name' => 'Active']);

        $procuct = Product::factory()->count(3)->create([
            'category_id' => $category->id,
            'status_id' => $status->id,
        ]);

        $result = $this->repository->getProductById($productId);

        $this->assertInstanceOf(EntitiesProduct::class, $result);
        $this->assertEquals($procuct[0]->id, $result->getId());
        $this->assertEquals($procuct[0]->name, $result->getName());
    }

    #[TestWith([1])]
    # php artisan test --filter=ProductRepositoryTest::test_returns_empty_product_by_id
    public function test_returns_empty_product_by_id(int $productId)
    {
        $result = $this->repository->getProductById($productId);

        $this->assertIsNotObject($result);
    }

    # php artisan test --filter=ProductRepositoryTest::test_save_product_and_return_entities_product
    public function test_save_product_and_return_entities_product()
    {
        $category = Category::factory()->create();
        $status = Status::factory()->create();

        $entityProduct = new EntitiesProduct(
            id: null,
            name: 'Product 1',
            brand: 'Brand 1',
            category: new ValueObjectsCategory($category->id, $category->name),
            description: 'Description 1',
            quantityInStock: 50,
            serialNumber: 'SN001',
            dateOfAcquisition: new DateTime('2025-08-09 10:34:00'),
            status: new ValueObjectsStatus($status->id, $status->name)
        );

        $result = $this->repository->saveProduct($entityProduct);

        $this->assertInstanceOf(EntitiesProduct::class, $result);
        $this->assertNotNull($result->getId());
        $this->assertSame('Product 1', $result->getName());
        $this->assertSame('Brand 1', $result->getBrand());
        $this->assertSame('Description 1', $result->getDescription());
        $this->assertSame(50, $result->getQuantityInStock());
        $this->assertSame('SN001', $result->getSerialNumber());
        $this->assertSame($category->id, $result->getCategory()->getId());
        $this->assertSame($status->id, $result->getStatus()->getId());

        // Valida que realmente salvou no banco
        $this->assertDatabaseHas('products', [
            'id' => $result->getId(),
            'name' => 'Product 1',
            'brand' => 'Brand 1',
            'category_id' => $category->id,
            'status_id' => $status->id
        ]);
    }
}