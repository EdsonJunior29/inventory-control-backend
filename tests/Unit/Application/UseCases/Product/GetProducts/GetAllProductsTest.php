<?php

declare(strict_types=1);

use App\Application\UseCases\Products\GetProducts\GetAllProducts;
use App\Domain\IRepository\IProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\TestCase;

# php artisan test --filter=GetAllProductsTest
class GetAllProductsTest extends TestCase
{
    use RefreshDatabase;

    # php artisan test --filter=GetAllProductsTest::test_execute_return_products_paginate
    public function test_execute_return_products_paginate(): void
    {
        $repoMock = Mockery::mock(IProductRepository::class);
        $paginatorMock = Mockery::mock(LengthAwarePaginator::class);
        $paginatorMock->shouldReceive('isNotEmpty')->andReturn(true);

        $repoMock->shouldReceive('getAllProducts')
            ->once()
            ->andReturn($paginatorMock);

        $getAllProductsUseCases = new GetAllProducts($repoMock);
        $result = $getAllProductsUseCases->execute();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertTrue($result->isNotEmpty());
    }

    # php artisan test --filter=GetAllProductsTest::test_execute_return_3_product_for_page
    public function test_execute_return_3_product_for_page()
    {
        $products = new Collection([
            (object) ['id' => 1, 'name' => 'Product 1'],
            (object) ['id' => 2, 'name' => 'Product 2'],
            (object) ['id' => 3, 'name' => 'Product 3'],
        ]);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $products,
            $products->count(),
            15,
            1,
            ['path' => url('/api/products')]
        );

        $repoMock = Mockery::mock(IProductRepository::class);
        $repoMock->shouldReceive('getAllProducts')
            ->once()
            ->andReturn($paginator);

        $getAllProductsUseCases = new GetAllProducts($repoMock);
        $result = $getAllProductsUseCases->execute();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(3, $result->items());
        $this->assertEquals('Product 1', $result->items()[0]->name);
        $this->assertEquals('Product 3', $result->items()[2]->name);
    }

    # php artisan test --filter=GetAllProductsTest::test_execute_return_no_product
    public function test_execute_return_no_product()
    {
        $repoMock = Mockery::mock(IProductRepository::class);
        $repoMock->shouldReceive('getAllProducts')
            ->once()
            ->andReturn(null);

        $getAllProductsUseCases = new GetAllProducts($repoMock);
        $result = $getAllProductsUseCases->execute();

        $this->assertEmpty($result);
    }
    
}