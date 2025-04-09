<?php

namespace App\Domain\Entities;

class Supplier
{
    private int $id;
    private string $name;
    private string $email;
    private string $phone;
    private string $cnpj;

    public function __construct(int $id, string $name, string $email, string $phone, string $cnpj)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->cnpj = $cnpj;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getCnpj(): string
    {
        return $this->cnpj;
    }
}