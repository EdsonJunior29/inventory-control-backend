<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Services;

use App\Domain\IRepository\ISupplierRepository;
use App\Domain\Services\SupplierServices\SupplierService;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

# php artisan test --filter=SupplierServiceTest
class SupplierServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $supplierRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan("migrate");
        $this->supplierRepositoryMock = $this->createMock(ISupplierRepository::class);
    }

    # php artisan test --filter=SupplierServiceTest::test_get_all_supplier_returns_suppliers
    public function test_get_all_supplier_returns_suppliers()
    {
        Supplier::factory()->count(5)->create();

        $supplierService = new SupplierService($this->supplierRepositoryMock);
        $suppliers = $supplierService->getAllSupliers();

        $this->assertInstanceOf(LengthAwarePaginator::class, $suppliers);
        $this->assertFalse($suppliers->isEmpty());
        $this->assertEquals(5, $suppliers->total());
        $this->assertCount(5, $suppliers->items());
    }

    # php artisan test --filter=SupplierServiceTest::test_get_all_supplier
    public function test_get_all_supplier_return_empty()
    {
        $emptyPaginator = new LengthAwarePaginator(collect([]), 0, 15);

        $this->supplierRepositoryMock
            ->method('getAllSupplier')
            ->willReturn($emptyPaginator);

        $supplierService = new SupplierService($this->supplierRepositoryMock);

        $response = $supplierService->getAllSupliers();

        //Algumas forma de validar se o response está vazio.
        $this->assertEmpty($response);
        $this->assertTrue($response->isEmpty());
    }

    #[TestWith([1])]
    # php artisan test --filter=SupplierServiceTest::test_get_supplier_by_id
    public function test_get_supplier_by_id(int $supplierId)
    {
        $supplier = Supplier::factory()->create(['id' => $supplierId, 'name' => 'Supplier Test']);

        $this->supplierRepositoryMock
            ->method('getSupplierById')
            ->willReturn(collect([$supplier]));

        $supplierService = new SupplierService($this->supplierRepositoryMock);
        $suppliers = $supplierService->getSupplierById($supplierId);

        $this->assertEquals($supplier->name, $suppliers->name);
    }

    # php artisan test --filter=SupplierServiceTest::test_get_supplier_by_id_throws_empty_data
    public function test_get_supplier_by_id_throws_empty_data()
    {
        $this->supplierRepositoryMock->method('getSupplierById')
            ->willReturn(null);

        $supplierService = new SupplierService($this->supplierRepositoryMock);
        $response = $supplierService->getSupplierById(999);

        $this->assertEmpty($response);
    }

    #[TestWith([5000])]
    # php artisan test --filter=SupplierServiceTest::test_delete_supplier_with_success
    public function test_delete_supplier_with_success(int $supplierId)
    {
        Supplier::factory()->create(['id' => $supplierId, 'name' => 'Supplier Test']);
        
        $this->supplierRepositoryMock
            ->method('deleteSupplierById')
            ->willReturn(null);

        $supplierService = new SupplierService($this->supplierRepositoryMock);
        $supplier = $supplierService->deleteSupplierById($supplierId);

        $this->assertNull($supplier);
    }

    #[TestWith([5000])]
    # php artisan test --filter=SupplierServiceTest::test_delete_supplier_by_id_throws_empty_data
    public function test_delete_supplier_by_id_throws_empty_data(int $supplierId)
    {
        $this->supplierRepositoryMock->method('deleteSupplierById')
            ->willReturn(null);

        $supplierService = new SupplierService($this->supplierRepositoryMock);
        $response = $supplierService->getSupplierById($supplierId);

        $this->assertEmpty($response);
    }

}