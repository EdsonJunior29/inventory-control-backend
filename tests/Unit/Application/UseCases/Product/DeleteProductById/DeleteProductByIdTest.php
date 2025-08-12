<?php

declare(strict_types=1);

use App\Application\UseCases\Products\DeleteProductById\DeleteProductById;
use App\Domain\IRepository\IProductRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\TestWith;

# php artisan test --filter=DeleteProductByIdTest
class DeleteProductByIdTest extends TestCase
{
    use RefreshDatabase;

    private $productRepositoryMock;

    
    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepositoryMock = Mockery::mock(IProductRepository::class);
    }

    #[TestWith([1])]
    # php artisan test --filter=DeleteProductByIdTest::test_delete_a_product_successfully
    public function test_delete_a_product_successfully(int $productId)
    {
        $this->productRepositoryMock
            ->shouldReceive('productExists')
            ->with($productId)
            ->andReturn(true);

        $this->productRepositoryMock
            ->shouldReceive('deleteById')
            ->with($productId)
            ->andReturn(true);

        $productDeleteById = new DeleteProductById(
            $this->productRepositoryMock
        );

        $result =  $productDeleteById->execute($productId);

        $this->assertTrue($result);
    }

    #[TestWith([1])]
    # php artisan test --filter=DeleteProductByIdTest::test_delete_a_product_throw_exception
    public function test_delete_a_product_throw_exception(int $productId)
    {
        $this->productRepositoryMock
            ->shouldReceive('productExists')
            ->with($productId)
            ->andReturn(false);

        $productDeleteById = new DeleteProductById(
            $this->productRepositoryMock
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product not found.');

        $productDeleteById->execute($productId);

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