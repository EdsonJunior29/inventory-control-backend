<?php

namespace Tests\Unit\Infra\Repositories\Supplier;

use App\Application\DTOs\Suppliers\SupplierInputDto;
use App\Application\Resources\Suppliers\SupplierResources;
use App\Domain\Entities\Supplier as EntitiesSupplier;
use App\Domain\Exceptions\SupplierNotFoundException;
use App\Infra\Repositories\Supplier\SupplierRepository;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

# php artisan test --filter=SupplierRepositoryTest
class SupplierRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private SupplierRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new SupplierRepository();
    }

    # php artisan test --filter=SupplierRepositoryTest::test_getAllSupplier_returns_paginated_results
    public function test_getAllSupplier_returns_paginated_results()
    {
        Supplier::factory()->count(15)->create();

        $result = $this->repository->getAllSupplier();

        $this->assertInstanceOf(SupplierResources::class, $result['data'][0]);
        $this->assertEquals(5, $result['meta']['per_page']);
        $this->assertEquals(15, $result['meta']['total']);
    }

    # php artisan test --filter=SupplierRepositoryTest::test_getAllSupplier_returns_correct_dto_structure
    public function test_getAllSupplier_returns_correct_dto_structure()
    {
        Supplier::factory()->create([
            'name' => 'Fornecedor DTO Test'
        ]);

        $result = $this->repository->getAllSupplier();
        $supplierData = $result['data'][0];
        
        $this->assertEquals('Fornecedor DTO Test', $supplierData['name']);
        $this->assertArrayHasKey('id', $supplierData);
    }

    # php artisan test --filter=SupplierRepositoryTest::test_getAllSupplier_returns_only_non_deleted_suppliers
    public function test_getAllSupplier_returns_only_non_deleted_suppliers()
    {
        Supplier::factory()->create(['name' => 'Ativo']);
        $deletedSupplier = Supplier::factory()->create(['name' => 'Deletado']);
        $deletedSupplier->delete();

        $result = $this->repository->getAllSupplier();

        $this->assertInstanceOf(SupplierResources::class, $result['data'][0]);
        $this->assertEquals(1, $result['meta']['total']);
        $this->assertEquals('Ativo', $result['data'][0]->name);
    }

    # php artisan test --filter=SupplierRepositoryTest::test_getSupplierById_returns_correct_resource
    public function test_getSupplierById_returns_correct_resource()
    {
       $supplier = Supplier::factory()->create([
           'name' => 'Fornecedor para Busca'
       ]);

       $supplierResouce = $this->repository->getSupplierById($supplier->id);

       $this->assertEquals('Fornecedor para Busca', $supplierResouce->resource['name']);
    }

    # php artisan test --filter=SupplierRepositoryTest::test_deleteSupplierById_marks_as_deleted
    public function test_deleteSupplierById_marks_as_deleted()
    {
        $supplier = Supplier::factory()->create();

        $result = $this->repository->deleteSupplierById($supplier->id);

        $this->assertEquals(1, $result);
        $this->assertSoftDeleted($supplier);
    }

    # php artisan test --filter=SupplierRepositoryTest::test_deleteSupplierById_returns_zero_when_already_deleted
    public function test_deleteSupplierById_returns_zero_when_already_deleted()
    {
        $supplier = Supplier::factory()->create();
        $supplier->delete();

        $result = $this->repository->deleteSupplierById($supplier->id);

        $this->assertEquals(0, $result);
    }

    # php artisan test --filter=SupplierRepositoryTest::test_save_creates_new_supplier_and_returns_entit
    public function test_save_creates_new_supplier_and_returns_entity()
    {
        $inputDto = new SupplierInputDto(
            name: 'Fornecedor SQLite',
            email: 'sqlite@test.com',
            phone: '11999991111',
            cnpj: '11.111.111/0001-11'
        );

        $entity = $this->repository->save($inputDto);

        $this->assertInstanceOf(EntitiesSupplier::class, $entity);
        $this->assertDatabaseHas('suppliers', [
            'name' => 'Fornecedor SQLite',
            'cnpj' => '11.111.111/0001-11'
        ]);
    }
    
    # php artisan test --filter=SupplierRepositoryTest::test_update_updates_supplier_and_returns_true
    public function test_update_updates_supplier_and_returns_true()
    {
        // Arrange
        $supplier = Supplier::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'phone' => '1234567890',
            'cnpj' => '12345678901234'
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ];

        $result = $this->repository->update($supplier->id, $updateData);

        $this->assertTrue($result);
        $updatedSupplier = Supplier::find($supplier->id);
        $this->assertEquals('Updated Name', $updatedSupplier->name);
        $this->assertEquals('updated@example.com', $updatedSupplier->email);
    }

    # php artisan test --filter=SupplierRepositoryTest::test_update_throws_exception_when_supplier_not_found
    public function test_update_throws_exception_when_supplier_not_found()
    {
        $nonExistentId = 9999;

        $this->expectException(SupplierNotFoundException::class);
        $this->expectExceptionMessage("Supplier with ID {$nonExistentId} not found");

        $this->repository->update($nonExistentId, ['name' => 'Test']);
    }

}