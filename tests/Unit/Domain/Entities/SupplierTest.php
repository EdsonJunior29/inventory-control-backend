<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Supplier;
use Tests\TestCase;

# php artisan test --filter=SupplierTest
class SupplierTest extends TestCase
{
    # php artisan test --filter=SupplierTest::test_supplier_constructor_and_getters
    public function test_supplier_constructor_and_getters(): void
    {
        $supplier = new Supplier(
            1,
            'Supplier Test',
            'test@supplier.com',
            '+55 11 99999-9999',
            '12345678000195'
        );

        $this->assertEquals(1, $supplier->getId());
        $this->assertEquals('Supplier Test', $supplier->getName());
        $this->assertEquals('test@supplier.com', $supplier->getEmail());
        $this->assertEquals('+55 11 99999-9999', $supplier->getPhone());
        $this->assertEquals('12345678000195', $supplier->getCnpj());
    }

    # php artisan test --filter=SupplierTest::test_get_id
    public function test_get_id(): void
    {
        $supplier = new Supplier(1, 'Supplier Test', 'test@supplier.com', '+55 11 99999-9999', '12345678000195');
        $this->assertEquals(1, $supplier->getId());
    }

    # php artisan test --filter=SupplierTest::test_get_name
    public function test_get_name(): void
    {
        $supplier = new Supplier(1, 'Supplier Test', 'test@supplier.com', '+55 11 99999-9999', '12345678000195');
        $this->assertEquals('Supplier Test', $supplier->getName());
    }

    # php artisan test --filter=SupplierTest::test_get_email
    public function test_get_email(): void
    {
        $supplier = new Supplier(1, 'Supplier Test', 'test@supplier.com', '+55 11 99999-9999', '12345678000195');
        $this->assertEquals('test@supplier.com', $supplier->getEmail());
    }

    # php artisan test --filter=SupplierTest::test_get_phone
    public function test_get_phone(): void
    {
        $supplier = new Supplier(1, 'Supplier Test', 'test@supplier.com', '+55 11 99999-9999', '12345678000195');
        $this->assertEquals('+55 11 99999-9999', $supplier->getPhone());
    }

    # php artisan test --filter=SupplierTest::test_get_cnpj
    public function test_get_cnpj(): void
    {
        $supplier = new Supplier(1, 'Supplier Test', 'test@supplier.com', '+55 11 99999-9999', '12345678000195');
        $this->assertEquals('12345678000195', $supplier->getCnpj());
    }
}