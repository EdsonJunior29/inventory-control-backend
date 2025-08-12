<?php

namespace App\Application\DTOs\Products;

use DateTime;

class ProductInputDto
{
    public function __construct(
        public string $name,
        public string $brand,
        public int $categoryId,
        public int $quantityInStock,
        public int $statusId,
        public ?DateTime $dateOfAcquisition = null, 
        public ?string $description = null,
    ) {
        $this->dateOfAcquisition = $dateOfAcquisition ?? new DateTime();
        $this->description = $description ?? '';
    } 
}