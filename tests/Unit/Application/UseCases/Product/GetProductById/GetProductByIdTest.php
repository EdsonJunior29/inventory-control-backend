<?php

declare(strict_types=1);

use App\Application\UseCases\Products\GetProductById\GetProductById;
use App\Domain\IRepository\IProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\TestWith;
use App\Domain\Entities\Product as EntitiesProduct;
use App\Models\Category;
use App\Models\Status;
use Tests\TestCase;

# php artisan test --filter=GetProductByIdTest
class GetProductByIdTest extends TestCase
{
    use RefreshDatabase;

    #[TestWith([1])]
    # php artisan test --filter=GetProductByIdTest::test_execute_return_product_by_id
    public function test_execute_return_product_by_id(int $productId)
    {
        $iProductRepository = Mockery::mock(IProductRepository::class);

        $status = Status::factory()->create();
        $category = Category::factory()->create();
        $productEntity = new EntitiesProduct(
            id: 1,
            name: 'Product 1',
            brand: 'Brand 1',
            category: new \App\Domain\ValueObjects\Category(
                1,
                $category->name
            ),
            description: 'Description 1',
            quantityInStock: 10,
            serialNumber: 'SN001',
            dateOfAcquisition: new DateTime('2023-01-01'),
            status: new \App\Domain\ValueObjects\Status(
                1,
                $status->name
            )
        );

        $iProductRepository->shouldReceive('getProductById')
            ->once()
            ->with($productId)
            ->andReturn($productEntity);

        $this->app->instance(IProductRepository::class, $iProductRepository);

        $getProductByidUseCases = new GetProductById($iProductRepository);
        $result = $getProductByidUseCases->execute($productId);

        $this->assertEquals($productEntity->getId(), $result->getId());
        $this->assertEquals($productEntity->getName(), $result->getName());
        $this->assertEquals($productEntity->getCategory()->getName(), $result->getCategory()->getName());
        $this->assertEquals($productEntity->getStatus()->getName(), $result->getStatus()->getName());
    }
}