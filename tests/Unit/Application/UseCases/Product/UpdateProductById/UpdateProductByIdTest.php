<?php

declare(strict_types=1);

use App\Application\DTOs\Products\ProductInputDto;
use App\Application\UseCases\Products\UpdateProductById\UpdateProductById;
use App\Domain\Entities\Product;
use App\Domain\IRepository\ICategoryRepository;
use App\Domain\IRepository\IProductRepository;
use App\Domain\IRepository\IStatusRepository;
use App\Domain\ValueObjects\Category as ValueObjectsCategory;
use App\Domain\ValueObjects\Status as ValueObjectsStatus;
use App\Models\Category;
use App\Models\Product as ModelsProduct;
use App\Models\Status;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\TestWith;

# php artisan test --filter=UpdateProductByIdTest
class UpdateProductByIdTest extends TestCase
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

    # php artisan test --filter=UpdateProductByIdTest::test_update_a_product_successfully
    public function test_update_a_product_successfully()
    {
        $category = Category::factory()->create();
        $status = Status::factory()->create();

        $productModel = ModelsProduct::factory()->create([
            'category_id' => $category->id,
            'status_id' => $status->id,
        ]);

        $this->productRepositoryMock
            ->shouldReceive('getProductById')
            ->with($productModel->id)
            ->andReturnUsing(function () use ($category, $status, $productModel) {
                return new Product(
                    id: $productModel->id,
                    name: $productModel->name,
                    brand: $productModel->brand,
                    category: new ValueObjectsCategory($category->id, $category->name),
                    description: $productModel->description,
                    quantityInStock: $productModel->quantity_in_stock,
                    serialNumber: $productModel->serial_number,
                    dateOfAcquisition: new DateTime($productModel->date_of_acquisition),
                    status: new ValueObjectsStatus($status->id, $status->name)
                );
            });

        $this->productRepositoryMock
            ->shouldReceive('updateProduct')
            ->with(Mockery::type(Product::class))
            ->andReturnUsing(function () use ($category, $status, $productModel) {
                return new Product(
                    id: $productModel->id,
                    name: $productModel->name,
                    brand: $productModel->brand,
                    category: new ValueObjectsCategory($category->id, $category->name),
                    description: $productModel->description,
                    quantityInStock: $productModel->quantity_in_stock,
                    serialNumber: $productModel->serial_number,
                    dateOfAcquisition: new DateTime($productModel->date_of_acquisition),
                    status: new ValueObjectsStatus($status->id, $status->name)
                );
            });

        
        $productUpdateById = new UpdateProductById(
            $this->productRepositoryMock,
            $this->categoryRepositoryMock,
            $this->statusRepositoryMock
        );

        $dto = new ProductInputDto(
            'Product 1',
            'Brand 1',
            $category->id,
            10,
            $status->id,
            new \DateTime('2023-01-01'),
            'Description 1'
        );

        $result = $productUpdateById->execute($productModel->id, $dto);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertSame('Product 1', $result->getName());
        $this->assertSame('Brand 1', $result->getBrand());
        $this->assertSame($category->name, $result->getCategory()->getName());
        $this->assertSame($status->name, $result->getStatus()->getName());
    }

    #[TestWith([1])]
    # php artisan test --filter=UpdateProductByIdTest::test_update_a_product_and_throw_exception
    public function test_update_a_product_and_throw_exception(int $productId)
    {
        $category = Category::factory()->create();
        $status = Status::factory()->create();

        $this->productRepositoryMock
            ->shouldReceive('getProductById')
            ->with($productId)
            ->andReturn(null);
        
        $productUpdateById = new UpdateProductById(
            $this->productRepositoryMock,
            $this->categoryRepositoryMock,
            $this->statusRepositoryMock
        );

        $dto = new ProductInputDto(
            'Product 1',
            'Brand 1',
            $category->id,
            10,
            $status->id,
            new \DateTime('2023-01-01'),
            'Description 1'
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product not found');

        $productUpdateById->execute($productId, $dto);
    }


    protected function tearDown(): void
    {
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        Mockery::close();
        parent::tearDown();
    }

}