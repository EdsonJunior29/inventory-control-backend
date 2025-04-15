<?php

namespace App\Application\DTOs\Suppliers;

class SupplierInputDto
{
    public function __construct(
        public string $name,
        public ?string $email,
        public ?string $phone,
        public string $cnpj,
    ) {
    }
    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            cnpj: $data['cnpj'],
        );
    }
}