<?php

declare(strict_types=1);

use App\Application\DTOs\Suppliers\SupplierOutputDto;
use App\Application\UseCases\Supplier\GetSupplierById\GetSupplierById;
use App\Domain\Entities\Supplier as EntitiesSupplier;
use App\Domain\IRepository\ISupplierRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $entitiesSupplier = new EntitiesSupplier(
            $supplierData['id'], 
            $supplierData['name'], 
            $supplierData['email'], 
            $supplierData['phone'], 
            $supplierData['cnpj']
        );

        $repoMock->shouldReceive('getSupplierById')
            ->once()
            ->with($supplierId)
            ->andReturn($entitiesSupplier);
        
        $getSupplierByIdUseCase = new GetSupplierById($repoMock);
        $result =  $getSupplierByIdUseCase->execute($supplierId);

        $this->assertInstanceOf(SupplierOutputDto::class, $result);
        $this->assertEquals($supplierData['name'], $result->name);
        $this->assertEquals($supplierData['email'], $result->email);
        $this->assertEquals($supplierData['phone'], $result->phone);
        $this->assertEquals($supplierData['cnpj'], $result->cnpj);
    }

    protected function tearDown(): void
    {
      Mockery::close();
      parent::tearDown();
    }
    
}