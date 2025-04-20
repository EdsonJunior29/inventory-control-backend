<?php

declare(strict_types=1);

use App\Application\DTOs\Suppliers\SupplierInputDto;
use App\Application\DTOs\Suppliers\SupplierOutputDto;
use App\Application\UseCases\Supplier\StoreSupplier\StoreSupplier;
use App\Domain\Entities\Supplier;
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

    # php artisan test --filter=StoreSupplierTest::it_stores_a_supplier_successfully
    public function test_stores_a_supplier_successfully()
    {
        $inputDto = new SupplierInputDto(
            name: 'Fornecedor Teste',
            email: 'fornecedor@teste.com',
            phone: '11999998888',
            cnpj: '11.222.333/0001-44'
        );
        
        $supplierEntity = new Supplier(
            id: 1,
            name: $inputDto->name,
            email: $inputDto->email,
            phone: $inputDto->phone,
            cnpj: $inputDto->cnpj,
        );
        
        $expectedOutputDto = SupplierOutputDto::fromEntity($supplierEntity);
        
        $this->supplierRepositoryMock->shouldReceive('save')
            ->once()
            ->with(Mockery::type(SupplierInputDto::class))
            ->andReturn($supplierEntity);

        $result = $this->storeSupplierUseCase->execute($inputDto);

        $this->assertInstanceOf(SupplierOutputDto::class, $result);
        $this->assertEquals($expectedOutputDto->id, $result->id);
        $this->assertEquals($expectedOutputDto->name, $result->name);
        $this->assertEquals($expectedOutputDto->email, $result->email);
        $this->assertEquals($expectedOutputDto->phone, $result->phone);
        $this->assertEquals($expectedOutputDto->cnpj, $result->cnpj);
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

        $supplierEntity = new Supplier(
            id: 2,
            name: $inputDto->name,
            email: $inputDto->email,
            phone: $inputDto->phone,
            cnpj: $inputDto->cnpj,
        );

        $expectedOutputDto = SupplierOutputDto::fromEntity($supplierEntity);

        // Expectativas
        $this->supplierRepositoryMock->shouldReceive('save')
            ->once()
            ->with(Mockery::type(SupplierInputDto::class))
            ->andReturn($supplierEntity);

        // Act
        $result = $this->storeSupplierUseCase->execute($inputDto);

        // Assert
        $this->assertInstanceOf(SupplierOutputDto::class, $result);
        $this->assertEquals($expectedOutputDto->id, $result->id);
        $this->assertEquals($expectedOutputDto->name, $result->name);
        $this->assertNull($result->email);
        $this->assertNull($result->phone);
        $this->assertEquals($expectedOutputDto->cnpj, $result->cnpj);
    }
}