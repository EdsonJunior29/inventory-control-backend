<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCase\User\CreateUser;

use App\Domain\IRepository\ISupplierRepository;
use App\Domain\UseCase\Supplier\GetSupplierById\GetSupplierById;
use PHPUnit\Framework\Attributes\TestWith;
use App\Models\Supplier;
use Tests\TestCase;

# php artisan test --filter=GetSupplierByIdTest
class GetSupplierByIdTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    #[TestWith([1])]
    # php artisan test --filter=GetSupplierByIdTest::test_execute_return_supplier_by_id
    public function test_execute_return_supplier_by_id(int $supplierId): void
    {
        $supplier = Supplier::factory()->count(5)->create();
    
        $supplierRepositoryMock = $this->createMock(ISupplierRepository::class);
        $supplierRepositoryMock->expects($this->once())
            ->method('getSupplierById')
            ->with($supplierId)
            ->willReturn($supplier);
        
        $getSupplierById = new GetSupplierById($supplierRepositoryMock);
        $result = $getSupplierById->execute($supplierId);

        $this->assertEquals($supplier, $result);
    }
}