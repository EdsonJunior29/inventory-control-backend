<?php

namespace App\Domain\Entities;

class Supplier
{
    private string $name;
    private string $email;
    private string $phone;
    private string $cnpj;

    public function __construct(string $name, string $email, string $phone, string $cnpj)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->cnpj = $cnpj;
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