<?php

namespace App\Application\DTOs\Products;

use DateTime;

class ProductInputDto
{
    public function __construct(
        public string $name,
        public string $brand,
        public ?int $categoryId = null,
        public int $quantityInStock,
        public ?DateTime $dateOfAcquisition = null, 
        public ?int $statusId = null, 
        public ?string $description = null,
    ) {
        $this->dateOfAcquisition ??= new DateTime();
        $this->description = $description ?? '';
    } 
}