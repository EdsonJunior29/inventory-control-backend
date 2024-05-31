<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Services;

use App\Domain\Exception\EmptyDataException;
use App\Domain\IRepository\ISupplierRepository;
use App\Domain\Services\SupplierServices\SupplierService;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

# php artisan test --filter=SupplierServiceTest
class SupplierServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan("migrate");
    }

    # php artisan test --filter=SupplierServiceTest::test_get_all_supplier_returns_suppliers
    public function test_get_all_supplier_returns_suppliers()
    {
        $supplierRepositoryMock = $this->createMock(ISupplierRepository::class);

        Supplier::factory()->count(5)->create();

        $supplierService = new SupplierService($supplierRepositoryMock);
        $suppliers = $supplierService->getAllSupliers();

        $this->assertInstanceOf(LengthAwarePaginator::class, $suppliers);
        $this->assertFalse($suppliers->isEmpty());
        $this->assertEquals(5, $suppliers->total());
        $this->assertCount(5, $suppliers->items());
    }

    # php artisan test --filter=SupplierServiceTest::test_get_all_supplier
    public function test_get_all_supplier_return_empty()
    {
        $supplierRepositoryMock = $this->createMock(ISupplierRepository::class);

        $emptyPaginator = new LengthAwarePaginator(collect([]), 0, 15);

        $supplierRepositoryMock
            ->method('getAllSupplier')
            ->willReturn($emptyPaginator);

        $supplierService = new SupplierService($supplierRepositoryMock);

        $this->expectException(EmptyDataException::class);

        $supplierService->getAllSupliers();

    }
}