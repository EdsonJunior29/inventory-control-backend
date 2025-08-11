<?php

declare(strict_types=1);

namespace Tests\Unit\Application\DTOs\Suppliers;

use App\Application\DTOs\Suppliers\SupplierInputDto;
use Tests\TestCase;

# php artisan test --filter=SupplierInputDtoTest
class SupplierInputDtoTest extends TestCase
{
    # php artisan test --filter=SupplierInputDtoTest::test_can_be_instantiated_with_constructor
    public function test_can_be_instantiated_with_constructor()
    {
        $data = [
            'name' => 'Supplier Test',
            'email' => 'supplierteste@teste.com',
            'phone' => '11999999999',
            'cnpj' => '12345678000195',
        ];

        $dto = new SupplierInputDto(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'],
            cnpj: $data['cnpj']
        );

        $this->assertInstanceOf(SupplierInputDto::class, $dto);
        $this->assertEquals($data['name'], $dto->name);
        $this->assertEquals($data['email'], $dto->email);
        $this->assertEquals($data['phone'], $dto->phone);
        $this->assertEquals($data['cnpj'], $dto->cnpj);
    }

    # php artisan test --filter=SupplierInputDtoTest::test_accepts_mull_for_optional_fields_in_constructor
    public function test_accepts_mull_for_optional_fields_in_constructor()
    {
        $data = [
            'name' => 'Supplier Test',
            'email' => null,
            'phone' => null,
            'cnpj' => '12345678000195',
        ];

        $dto = new SupplierInputDto(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'],
            cnpj: $data['cnpj']
        );

        $this->assertInstanceOf(SupplierInputDto::class, $dto);
        $this->assertEquals($data['name'], $dto->name);
        $this->assertNull($dto->email);
        $this->assertNull($dto->phone);
        $this->assertEquals($data['cnpj'], $dto->cnpj);
    }

    # php artisan test --filter=SupplierInputDtoTest::test_from_request_creates_correct_dto
    public function test_from_request_creates_correct_dto()
    {
        $data = [
            'name' => 'Supplier Test',
            'email' => 'supplierteste@teste.com',
            'phone' => '11999999999',
            'cnpj' => '12345678000195',
        ];

        $dto = SupplierInputDto::fromRequest($data);

        $this->assertInstanceOf(SupplierInputDto::class, $dto);
        $this->assertEquals($data['name'], $dto->name);
        $this->assertEquals($data['email'], $dto->email);
        $this->assertEquals($data['phone'], $dto->phone);
        $this->assertEquals($data['cnpj'], $dto->cnpj);
    }

    # php artisan test --filter=SupplierInputDtoTest::test_requires_name_and_cnpj_fields
    public function test_requires_name_and_cnpj_fields()
    {
        // Teste para verificar se campos obrigatórios são realmente obrigatórios
        // Como PHP é fracamente tipado, esses testes verificam o comportamento esperado

        $this->expectException(\Error::class);
        new SupplierInputDto(
            name: null,
            email: null,
            phone: null,
            cnpj: '11.222.333/0001-44'
        );
    }

    # php artisan test --filter=SupplierInputDtoTest::test_handles_missing_optional_fields_in_from_request
    public function test_handles_missing_optional_fields_in_from_request()
    {
        $requestData = [
            'name' => 'Fornecedor Sem Opcionais',
            'cnpj' => '33.444.555/0001-66'
        ];

        $dto = SupplierInputDto::fromRequest($requestData);

        $this->assertInstanceOf(SupplierInputDto::class, $dto);
        $this->assertEquals($requestData['name'], $dto->name);
        $this->assertNull($dto->email);
        $this->assertNull($dto->phone);
        $this->assertEquals($requestData['cnpj'], $dto->cnpj);
    }
}