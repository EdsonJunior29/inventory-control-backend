<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCase\User\CreateUser;

use App\Domain\IRepository\ISupplierRepository;
use App\Domain\UseCase\Supplier\GetSuppliers\GetAllSupplier;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

# php artisan test --filter=GetSuppliersTest
class GetSuppliersTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_execute_return_suppliers(): void
    {
        $supplierRepositoryMock = $this->createMock(ISupplierRepository::class);

        $suppliers = Supplier::factory()->count(5)->create();

        $suppliersData = $suppliers->map(function ($supplier) {
            return ['id' => $supplier->id, 'name' => $supplier->name];
        });

        $suppliersMock = new LengthAwarePaginator($suppliersData, $suppliers->count(), 15, 1);
        
        $supplierRepositoryMock->expects($this->once())
            ->method('getAllSupplier')
            ->willReturn( $suppliersMock );
        
        $getSuppliers = new GetAllSupplier($supplierRepositoryMock);
        $result = $getSuppliers->execute();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals($suppliersMock, $result);
    }
}