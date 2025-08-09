<?php

namespace App\Domain\Entities;

use App\Domain\Exceptions\MinimumQuantityInStockException;
use App\Domain\ValueObjects\Category;
use App\Domain\ValueObjects\Status;
use DateTime;
use Ramsey\Uuid\Uuid;

class Product
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $brand,
        private Category $category,
        private string $description,
        private int $quantityInStock,
        private string $serialNumber,
        private DateTime $dateOfAcquisition,
        private Status $status,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getQuantityInStock(): int
    {
        return $this->quantityInStock;
    }

    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }

    public function getDateOfAcquisition(): DateTime
    {
        return $this->dateOfAcquisition;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public static function createWithAutoSerial(
        string $name,
        string $brand,
        Category $category,
        string $description,
        int $quantityInStock,
        string $serialNumber,
        DateTime $dateOfAcquisition,
        Status $status,
    ): self {
        $quantityInStock = self::validadeQuantityInStock($quantityInStock);
        return new self(
            id: null,
            name: $name,
            brand: $brand,
            category: $category,
            description: $description,
            quantityInStock: $quantityInStock,
            serialNumber: $serialNumber,
            dateOfAcquisition: $dateOfAcquisition,
            status: $status,
        );
    }

    public static function validadeQuantityInStock(int $quantity): int
    {
        if ($quantity <= 0) {
            throw new MinimumQuantityInStockException();
        }
        
        return $quantity;
    }

    public static function generateSerialNumber(): string
    {
        return Uuid::uuid4()->toString();
    }
}