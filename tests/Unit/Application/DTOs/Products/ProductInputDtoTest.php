<?php

declare(strict_types=1);

namespace Tests\Unit\Application\DTOs\Products;

use App\Application\DTOs\Products\ProductInputDto;
use DateTime;
use Tests\TestCase;

# php artisan test --filter=ProductInputDtoTest
class ProductInputDtoTest extends TestCase
{
    # php artisan test --filter=ProductInputDtoTest::test_can_be_instantiated_with_constructor
    public function test_can_be_instantiated_with_constructor()
    {
        $data = [
            "name" => "teste criação de um produto 9",
            "brand" => "Nome da marca 9",  
            "category_id" => 3,
            "quantity_in_stock" => 5000,
            "status_id" => 3
        ];

        $dto = new ProductInputDto(
            name: $data['name'],
            brand: $data['brand'],
            categoryId: $data['category_id'],
            quantityInStock: $data['quantity_in_stock'],
            statusId: $data['status_id']
        );


        $this->assertInstanceOf(ProductInputDto::class, $dto);
        $this->assertEquals($data['name'], $dto->name);
        $this->assertEquals($data['brand'], $dto->brand);
        $this->assertEquals($data['category_id'], $dto->categoryId);
        $this->assertEquals($data['quantity_in_stock'], $dto->quantityInStock);
        $this->assertEquals($data['status_id'], $dto->statusId);
        $this->assertEmpty($dto->description);
        $this->assertInstanceOf(\DateTime::class, $dto->dateOfAcquisition);
    }
}