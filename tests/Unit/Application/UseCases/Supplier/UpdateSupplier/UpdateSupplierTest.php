<?php

declare(strict_types=1);

namespace Tests\Unit\Application\UseCases\Supplier\UpdateSupplier;

use App\Application\UseCases\Supplier\UpdateSupplier\UpdateSupplier;
use App\Domain\IRepository\ISupplierRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class UpdateSupplierTest extends TestCase
{

    private $supplierRepositoryMock;
    private $updateSupplierUseCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->supplierRepositoryMock = Mockery::mock(ISupplierRepository::class);
        $this->updateSupplierUseCase = new UpdateSupplier($this->supplierRepositoryMock);
    }

    # php artisan test --filter=UpdateSupplierTest::test_updates_a_supplier_successfully
    public function test_updates_a_supplier_successfully()
    {
        $supplierId = 1;
        $data = [
            'name' => 'Updated Supplier',
            'email' => 'updated@teste.com',
            'phone' => '11999998888',
        ];

        // Configura o mock para esperar a chamada do método update
        $this->supplierRepositoryMock->shouldReceive('update')
            ->once()
            ->with($supplierId, $data)
            ->andReturn(true);

        $result = $this->updateSupplierUseCase->execute($supplierId, $data);

        $this->assertTrue($result);
    }

    # php artisan test --filter=UpdateSupplierTest::test_updates_a_supplier_with_invalid_data
    public function test_update_fails_when_repository_returns_false()
    {
        $supplierId = 1;
        $data = [
            'name' => 'Updated Supplier',
            'email' => 'updated@teste.com',
            'phone' => '11999998888',
        ];

        // Configura o mock para retornar false (simulando falha)
        $this->supplierRepositoryMock->shouldReceive('update')
            ->once()
            ->with($supplierId, $data)
            ->andReturn(false);

        $result = $this->updateSupplierUseCase->execute($supplierId, $data);

        $this->assertFalse($result);
    }
}