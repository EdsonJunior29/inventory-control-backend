<?php

namespace Tests\Unit\Infra\Repositories\Supplier;

use App\Application\DTOs\Suppliers\SupplierInputDto;
use App\Domain\Entities\Supplier as EntitiesSupplier;
use App\Domain\Exceptions\SupplierNotFoundException;
use App\Infra\Repositories\Supplier\SupplierRepository;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
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

    # php artisan test --filter=SupplierRepositoryTest::it_getAllSupplier_returns_paginated_results
    /** @test */
    public function it_getAllSupplier_returns_paginated_results()
    {
        Supplier::factory()->count(15)->create();

        $result = $this->repository->getAllSupplier();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(5, $result->items());
        $this->assertEquals(15, $result->total());
    }

    # php artisan test --filter=SupplierRepositoryTest::it_getAllSupplier_returns_correct_dto_structure
    /** @test */
    public function it_getAllSupplier_returns_correct_dto_structure()
    {
        Supplier::factory()->create([
            'name' => 'Fornecedor DTO Test'
        ]);

        $result = $this->repository->getAllSupplier();

        $this->assertEquals('Fornecedor DTO Test', $result->items()[0]->name);
        $this->assertObjectHasProperty('id', $result->items()[0]);
    }

    # php artisan test --filter=SupplierRepositoryTest::it_getAllSupplier_returns_only_non_deleted_suppliers
    /** @test */
    public function it_getAllSupplier_returns_only_non_deleted_suppliers()
    {
        Supplier::factory()->create(['name' => 'Ativo']);
        $deletedSupplier = Supplier::factory()->create(['name' => 'Deletado']);
        $deletedSupplier->delete();

        $result = $this->repository->getAllSupplier();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(1, $result->items());
        $this->assertEquals('Ativo', $result->items()[0]->name);
    }

    # php artisan test --filter=SupplierRepositoryTest::it_getSupplierById_returns_correct_entity
    /** @test */
    public function it_getSupplierById_returns_correct_entity()
    {
       $supplier = Supplier::factory()->create([
           'name' => 'Fornecedor para Busca'
       ]);

       $entity = $this->repository->getSupplierById($supplier->id);

       $this->assertEquals('Fornecedor para Busca', $entity->getName());
    }
 
    # php artisan test --filter=SupplierRepositoryTest::it_getSupplierById_throws_exception_when_not_found
    /** @test */
    public function it_getSupplierById_throws_exception_when_not_found()
    {
        $this->expectException(SupplierNotFoundException::class);
        $this->repository->getSupplierById(9999);
    }

    # php artisan test --filter=SupplierRepositoryTest::it_deleteSupplierById_marks_as_deleted
    /** @test */
    public function it_deleteSupplierById_marks_as_deleted()
    {
        $supplier = Supplier::factory()->create();

        $result = $this->repository->deleteSupplierById($supplier->id);

        $this->assertEquals(1, $result);
        $this->assertSoftDeleted($supplier);
    }

    # php artisan test --filter=SupplierRepositoryTest::it_deleteSupplierById_returns_zero_when_already_deleted
    /** @test */
    public function it_deleteSupplierById_returns_zero_when_already_deleted()
    {
        $supplier = Supplier::factory()->create();
        $supplier->delete();

        $result = $this->repository->deleteSupplierById($supplier->id);

        $this->assertEquals(0, $result);
    }

    # php artisan test --filter=SupplierRepositoryTest::it_save_creates_new_supplier_and_returns_entit
    /** @test */
    public function it_save_creates_new_supplier_and_returns_entity()
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
    
}