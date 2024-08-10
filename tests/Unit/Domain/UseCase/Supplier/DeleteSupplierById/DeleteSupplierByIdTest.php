<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCase\User\CreateUser;

use App\Domain\Exception\InternalServerErrorException;
use App\Domain\IRepository\ISupplierRepository;
use App\Domain\UseCase\Supplier\DeleteSupplierById\DeleteSupplierById;
use App\Models\Supplier;
use Exception;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\TestWith;

# php artisan test --filter=DeleteSupplierByIdTest
class DeleteSupplierByIdTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    #[TestWith([1])]
    # php artisan test --filter=DeleteSupplierByIdTest::test_execute_delete_supplier_by_id
    public function test_execute_delete_supplier_by_id(int $supplierId)
    {
        Supplier::factory()->count(5)->create();
        $supplier = Supplier::find($supplierId);
        $supplierRepositoryMock = $this->createMock(ISupplierRepository::class);

        $supplierRepositoryMock->expects($this->once())
            ->method('getSupplierById')
            ->with($supplierId)
            ->willReturn($supplier);

        $supplierRepositoryMock->expects($this->once())
            ->method('deleteSupplierById')
            ->with($supplierId)
            ->willReturn(true);
        
        $deleteSupplierById = new DeleteSupplierById($supplierRepositoryMock);
        $result =  $deleteSupplierById->execute($supplierId);

        $this->assertNull($result);
    }
}