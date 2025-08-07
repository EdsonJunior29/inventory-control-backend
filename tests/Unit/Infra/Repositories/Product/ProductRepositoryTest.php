<?php

namespace Tests\Unit\Infra\Repositories\Product;

use App\Domain\Entities\Product as EntitiesProduct;
use App\Infra\Repositories\Product\ProductRepository;
use App\Models\Category;
use App\Models\Product;
use App\Models\Status;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\TestWith;

# php artisan test --filter=ProductRepositoryTest
class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductRepository();
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
}