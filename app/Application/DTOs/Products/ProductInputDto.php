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
        public DateTime $dateOfAcquisition = new DateTime(), 
        public int $statusId, 
        public ?string $description = "",
    ) {} 
}