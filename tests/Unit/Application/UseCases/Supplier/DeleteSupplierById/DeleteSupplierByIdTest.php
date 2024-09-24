<?php

declare(strict_types=1);

use App\Application\UseCases\Supplier\DeleteSupplierById\DeleteSupplierById;
use App\Domain\IRepository\ISupplierRepository;
use App\Models\Supplier;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\TestWith;

# php artisan test --filter=DeleteSupplierByIdTest
class DeleteSupplierByIdTest extends TestCase
{
    use RefreshDatabase;

    protected $supplierRepositoryMock;
    protected $deleteSupplierByIdUseCases;

    protected function setUp(): void
    {
        parent::setUp();

        $this->supplierRepositoryMock = Mockery::mock(ISupplierRepository::class);
        $this->deleteSupplierByIdUseCases = new DeleteSupplierById($this->supplierRepositoryMock);
    }

    # php artisan test --filter=DeleteSupplierByIdTest::teste_execute_delete_supplier_by_id_success
    #[TestWith([1])]
    public function teste_execute_delete_supplier_by_id_success(int $supplierId): void
    {
        $supplier = Supplier::factory()->create([ 
            'id' => $supplierId,
            'name' => 'Supplier Test',
            'email' => 'norval49@example.net',
            'phone' => '(240) 725-5940'
        ]);

        $this->supplierRepositoryMock
           ->shouldReceive('getSupplierById')
           ->once()
           ->with($supplierId)
           ->andReturn($supplier);

        $this->supplierRepositoryMock
           ->shouldReceive('deleteSupplierById')
           ->once()
           ->with($supplierId);
        
        $this->deleteSupplierByIdUseCases->execute($supplierId);
    }

    # php artisan test --filter=DeleteSupplierByIdTest::teste_execute_delete_supplier_by_id_with_null_supplier
    #[TestWith([1])]
    public function teste_execute_delete_supplier_by_id_with_null_supplier(int $supplierId): void
    {
        $this->supplierRepositoryMock
            ->shouldReceive('getSupplierById')
            ->once()
            ->with($supplierId)
            ->andReturn(null);
    
        // Verificamos que o método deleteSupplierById não será chamado
        $this->supplierRepositoryMock
            ->shouldNotReceive('deleteSupplierById');
    
        $result = $this->deleteSupplierByIdUseCases->execute($supplierId);
    
        $this->assertNull($result);
    }
}