<?php

declare(strict_types=1);

use App\Application\UseCases\Supplier\GetSuppliers\GetAllSupplier;
use App\Domain\IRepository\ISupplierRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

# php artisan test --filter=GetAllSuppliersTest
class GetAllSuppliersTest extends TestCase
{
    use RefreshDatabase;

    # php artisan test --filter=GetAllSuppliersTest::test_execute_return_suppliers_paginate
    public function test_execute_return_suppliers_paginate(): void
    {
        $repoMock = Mockery::mock(ISupplierRepository::class);
        
        $paginatorMock = Mockery::mock(LengthAwarePaginator::class);
        $paginatorMock->shouldReceive('isNotEmpty')->andReturn(true);

        $repoMock->shouldReceive('getAllSupplier')
            ->once()
            ->andReturn($paginatorMock);
        
        $getAllSuppliersUseCases = new GetAllSupplier($repoMock);
        $result =  $getAllSuppliersUseCases->execute();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertTrue($result->isNotEmpty());
    }

    # php artisan test --filter=GetAllSuppliersTest::test_execute_return_5_suppliers_for_page
    public function test_execute_return_5_suppliers_for_page(): void
    {
        $suppliers = new Collection([
            (object) ['id' => 1, 'name' => 'Supplier 1'],
            (object) ['id' => 2, 'name' => 'Supplier 2'],
            (object) ['id' => 3, 'name' => 'Supplier 3'],
            (object) ['id' => 4, 'name' => 'Supplier 4'],
            (object) ['id' => 5, 'name' => 'Supplier 5'],
        ]);

        $paginator = new LengthAwarePaginator(
            $suppliers, // Itens atuais
            5,          // Total de itens
            5,          // Itens por página
            1,          // Página atual
            ['path' => url('/api/supplier/all-suppliers')]
        );

        $repoMock = Mockery::mock(ISupplierRepository::class);
        $repoMock->shouldReceive('getAllSupplier')
            ->once()
            ->andReturn($paginator);

        $getAllSuppliersUseCases = new GetAllSupplier($repoMock);
        $result =  $getAllSuppliersUseCases->execute();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(5, $result->items());
        $this->assertEquals('Supplier 1', $result->items()[0]->name);
        $this->assertEquals('Supplier 5', $result->items()[4]->name);
    }
}