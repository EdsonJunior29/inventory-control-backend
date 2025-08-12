<?php

declare(strict_types=1);

use App\Application\Resources\Suppliers\SupplierByIdResources;
use App\Application\UseCases\Supplier\GetSupplierById\GetSupplierById;
use App\Domain\IRepository\ISupplierRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

# php artisan test --filter=GetSupplierByIdTest
class GetSupplierByIdTest extends TestCase
{
    use RefreshDatabase;

    # php artisan test --filter=GetSupplierByIdTest::test_execute_return_suppliers_by_id
    #[TestWith([1])]
    public function test_execute_return_suppliers_by_id(int $supplierId): void
    {
        $repoMock = Mockery::mock(ISupplierRepository::class);

        $supplierData = [
            'id' => 1, 
            'name' => 'Supplier 1', 
            'email' => 'skyla72@example.net',
            'phone' => '+1-458-968-8063',
            'cnpj' => '12345678901234',
        ];

        $supplierResource = new SupplierByIdResources($supplierData);

        $repoMock->shouldReceive('getSupplierById')
            ->once()
            ->with($supplierId)
            ->andReturn($supplierResource);
        
        $getSupplierByIdUseCase = new GetSupplierById($repoMock);
        $result =  $getSupplierByIdUseCase->execute($supplierId);
        
        $this->assertEquals($supplierData['name'], $result->resource['name']);
        $this->assertEquals($supplierData['email'], $result->resource['email']);
        $this->assertEquals($supplierData['phone'], $result->resource['phone']);
        $this->assertEquals($supplierData['cnpj'], $result->resource['cnpj']);
    }

    protected function tearDown(): void
    {
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        Mockery::close();
        parent::tearDown();
    }
    
}