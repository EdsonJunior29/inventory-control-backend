<?php

declare(strict_types=1);

use App\Application\UseCases\Supplier\GetSupplierById\GetSupplierById;
use App\Domain\IRepository\ISupplierRepository;
use App\Models\Supplier;
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

        $supplier = new Supplier([
            'id' => 1, 
            'name' => 'Supplier 1', 
            'email' => 'skyla72@example.net',
            'phone' => '+1-458-968-8063'
        ]);

        $repoMock->shouldReceive('getSupplierById')
            ->once()
            ->with($supplierId)
            ->andReturn($supplier);
        
        $getSupplierByIdUseCase = new GetSupplierById($repoMock);
        $result =  $getSupplierByIdUseCase->execute($supplierId);

        $this->assertInstanceOf(Supplier::class, $result);
        $this->assertEquals($supplier->name, $result->name);
        $this->assertEquals($supplier->email, $result->email);
        $this->assertEquals($supplier->phone, $result->phone);
    }

    protected function tearDown(): void
    {
      Mockery::close();
      parent::tearDown();
    }
    
}