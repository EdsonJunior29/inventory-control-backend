<?php

declare(strict_types=1);

namespace Tests\Unit\Application\DTOs\Suppliers;

use App\Application\DTOs\Suppliers\SupplierOutputDto;
use App\Domain\Entities\Supplier;
use Tests\TestCase;

# php artisan test --filter=SupplierOutoutDtoTest
class SupplierOutoutDtoTest extends TestCase
{
    # php artisan test --filter=SupplierOutoutDtoTest::test_from_entity_creates_correct_dto
    public function test_from_entity_creates_correct_dto(): void
    {
        $supplier = new Supplier(
            1,
            'Supplier Test',
            'test@supplier.com',
            '+55 11 99999-9999',
            '12345678000195'
        );

        $dto = SupplierOutputDto::fromEntity($supplier);

        $this->assertInstanceOf(SupplierOutputDto::class, $dto);
        $this->assertEquals($supplier->getId(), $dto->id);
        $this->assertEquals($supplier->getName(), $dto->name);
        $this->assertEquals($supplier->getEmail(), $dto->email);
        $this->assertEquals($supplier->getPhone(), $dto->phone);
        $this->assertEquals($supplier->getCnpj(), $dto->cnpj);
    }

    # php artisan test --filter=SupplierOutoutDtoTest::test_from_entity_with_null_email
    public function test_from_entity_with_null_email(): void
    {
        $supplier = new Supplier(
            1,
            'Supplier Test',
            null,
            '+55 11 99999-9999',
            '12345678000195'
        );

        $dto = SupplierOutputDto::fromEntity($supplier);

        $this->assertNull($dto->email);
        $this->assertEquals($supplier->getPhone(), $dto->phone);
        $this->assertEquals($supplier->getCnpj(), $dto->cnpj);
    }

    # php artisan test --filter=SupplierOutoutDtoTest::test_from_entity_with_null_phone
    public function test_from_entity_with_null_phone(): void
    {
        $supplier = new Supplier(
            1,
            'Supplier Test',
            'test@supplier.com',
            null,
            '12345678000195'
        );

        $dto = SupplierOutputDto::fromEntity($supplier);

        $this->assertNull($dto->phone);
        $this->assertEquals($supplier->getEmail(), $dto->email);
        $this->assertEquals($supplier->getCnpj(), $dto->cnpj);
    }

    # php artisan test --filter=SupplierOutoutDtoTest::test_from_entity_with_null_cnpj
    public function test_from_entity_with_null_cnpj(): void
    {
        $supplier = new Supplier(
            1,
            'Supplier Test',
            'test@supplier.com',
            '+55 11 99999-9999',
            null
        );

        $dto = SupplierOutputDto::fromEntity($supplier);

        $this->assertNull($dto->cnpj);
        $this->assertEquals($supplier->getEmail(), $dto->email);
        $this->assertEquals($supplier->getPhone(), $dto->phone);
    }

    # php artisan test --filter=SupplierOutoutDtoTest::test_from_entity_with_all_null_optionals
    public function test_from_entity_with_all_null_optionals(): void
    {
        $supplier = new Supplier(
            1,
            'Supplier Test',
            null,
            null,
            null
        );

        $dto = SupplierOutputDto::fromEntity($supplier);

        $this->assertNull($dto->email);
        $this->assertNull($dto->phone);
        $this->assertNull($dto->cnpj);
        $this->assertEquals($supplier->getId(), $dto->id);
        $this->assertEquals($supplier->getName(), $dto->name);
    }
}