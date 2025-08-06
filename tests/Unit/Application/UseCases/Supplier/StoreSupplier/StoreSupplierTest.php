<?php

declare(strict_types=1);

use App\Application\DTOs\Suppliers\SupplierInputDto;
use App\Application\Resources\Suppliers\SupplierByIdResources;
use App\Application\UseCases\Supplier\StoreSupplier\StoreSupplier;
use App\Domain\IRepository\ISupplierRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

# php artisan test --filter=StoreSupplierTest
class StoreSupplierTest extends TestCase
{
    use RefreshDatabase;

    private $supplierRepositoryMock;
    private $storeSupplierUseCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->supplierRepositoryMock = Mockery::mock(ISupplierRepository::class);
        $this->storeSupplierUseCase = new StoreSupplier($this->supplierRepositoryMock);
    }

    # php artisan test --filter=StoreSupplierTest::test_stores_a_supplier_successfully
    public function test_stores_a_supplier_successfully()
    {
        $inputDto = new SupplierInputDto(
            name: 'Fornecedor Teste',
            email: 'fornecedor@teste.com',
            phone: '11999998888',
            cnpj: '11.222.333/0001-44'
        );
        
        $supplierResource = new SupplierByIdResources($inputDto);
        
        $this->supplierRepositoryMock->shouldReceive('save')
            ->once()
            ->with(Mockery::type(SupplierInputDto::class))
            ->andReturn($supplierResource);

        $result = $this->storeSupplierUseCase->execute($inputDto);

        $this->assertInstanceOf(SupplierByIdResources::class, $result);
        $this->assertEquals($inputDto->name, $result->resource->name);
        $this->assertEquals($inputDto->email, $result->resource->email);
        $this->assertEquals($inputDto->phone, $result->resource->phone);
        $this->assertEquals($inputDto->cnpj, $result->resource->cnpj);
    }

    # php artisan test --filter=StoreSupplierTest::test_handles_repository_exception
    public function test_handles_repository_exception()
    {
        $inputDto = new SupplierInputDto(
            name: 'Fornecedor Teste',
            email: 'fornecedor@teste.com',
            phone: '11999998888',
            cnpj: '11.222.333/0001-44'
        );

        // Expectativas para o mock do repositório
        $this->supplierRepositoryMock->shouldReceive('save')
            ->once()
            ->with(Mockery::type(SupplierInputDto::class))
            ->andThrow(new \Exception('Database error'));

        // Assert that the exception is thrown
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Database error');

        // Act
        $this->storeSupplierUseCase->execute($inputDto);
    }

    # php artisan test --filter=StoreSupplierTest::test_creates_supplier_with_minimal_required_data
    public function test_creates_supplier_with_minimal_required_data()
    {
        //Arrange
        $inputDto = new SupplierInputDto(
            name: 'Fornecedor Teste',
            email: null,
            phone: null,
            cnpj: '11.222.333/0001-44'
        );

        $supplierResource = new SupplierByIdResources($inputDto);

        $this->supplierRepositoryMock->shouldReceive('save')
            ->once()
            ->with(Mockery::type(SupplierInputDto::class))
            ->andReturn($supplierResource);

        // Act
        $result = $this->storeSupplierUseCase->execute($inputDto);

        // Assert
        $this->assertInstanceOf(SupplierByIdResources::class, $result);
        $this->assertEquals($inputDto->name, $result->resource->name);
        $this->assertNull($result->resource->email);
        $this->assertNull($result->resource->phone);
        $this->assertEquals($inputDto->cnpj, $result->resource->cnpj);
    }
}