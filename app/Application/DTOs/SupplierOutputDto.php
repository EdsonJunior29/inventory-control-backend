<?php

namespace App\Application\DTOs;

use App\Domain\Entities\Supplier;

class SupplierOutputDto
{
    public int $id;
    public string $name;
    public string $email;
    public string $phone;
    public string $cnpj;

    public function __construct(int $id, string $name, string $email, string $phone, string $cnpj)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->cnpj = $cnpj;
    }

    public static function fromEntity(Supplier $supplier): self
    {
        return new self(
            $supplier->getId(),
            $supplier->getName(),
            $supplier->getEmail(),
            $supplier->getPhone(),
            $supplier->getCnpj()
        );
    }
}