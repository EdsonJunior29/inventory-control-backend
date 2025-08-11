<?php

declare(strict_types=1);

use App\Application\DTOs\Products\ProductInputDto;
use App\Application\UseCases\Products\StoreProducts\StoreProduct;
use App\Domain\IRepository\ICategoryRepository;
use App\Domain\IRepository\IProductRepository;
use App\Domain\IRepository\IStatusRepository;
use App\Domain\ValueObjects\Category as ValueObjectsCategory;
use App\Domain\ValueObjects\Status as ValueObjectsStatus;
use App\Models\Category;
use App\Models\Status;
use Tests\TestCase;
use App\Domain\Entities\Product as EntitiesProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;

# php artisan test --filter=StoreProductTest
class StoreProductTest extends TestCase
{
    use RefreshDatabase;

    private $productRepositoryMock;
    private $categoryRepositoryMock;
    private $statusRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepositoryMock = Mockery::mock(IProductRepository::class);
        $this->categoryRepositoryMock = Mockery::mock(ICategoryRepository::class);
        $this->statusRepositoryMock = Mockery::mock(IStatusRepository::class);
    }

    # php artisan test --filter=StoreProductTest::test_stores_a_product_successfully
    public function test_stores_a_product_successfully()
    {
        $category = Category::factory()->create();
        $status = Status::factory()->create();
        
        // Mock do repositório de categorias
        $this->categoryRepositoryMock
            ->shouldReceive('getCategoryById')
            ->once()
            ->with($category->id)
            ->andReturn($category);

        // Mock do repositório de status
        $this->statusRepositoryMock
            ->shouldReceive('getStatusById')
            ->once()
            ->with($status->id)
            ->andReturn($status);

        $this->productRepositoryMock
            ->shouldReceive('saveProduct')
            ->with(Mockery::type(EntitiesProduct::class))
            ->andReturnUsing(function (EntitiesProduct $product) use ($category, $status) {
                return new EntitiesProduct(
                    id: 1,
                    name: $product->getName(),
                    brand: $product->getBrand(),
                    category: new ValueObjectsCategory($category->id, $category->name),
                    description: $product->getDescription(),
                    quantityInStock: $product->getQuantityInStock(),
                    serialNumber: $product->getSerialNumber(),
                    dateOfAcquisition: $product->getDateOfAcquisition(),
                    status: new ValueObjectsStatus($status->id, $status->name)
                );
            });

        $productCreatedUseCases = new StoreProduct(
            $this->productRepositoryMock,
            $this->categoryRepositoryMock,
            $this->statusRepositoryMock
        );

        $dto = new ProductInputDto(
            'Product 1',
            'Brand 1',
            $category->id,
            10,
            new \DateTime('2023-01-01'),
            $status->id,
            'Description 1'
        );
        
        $result = $productCreatedUseCases->execute($dto);

        $this->assertInstanceOf(EntitiesProduct::class, $result);
        $this->assertSame('Product 1', $result->getName());
        $this->assertSame('Brand 1', $result->getBrand());
        $this->assertSame($category->name, $result->getCategory()->getName());
        $this->assertSame($status->name, $result->getStatus()->getName());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}