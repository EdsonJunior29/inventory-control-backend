<?php

namespace App\Domain\Exceptions;

use Exception;

class SupplierNotFoundException extends Exception
{
    public function __construct(int $supplierId)
    {
        parent::__construct("Supplier with ID {$supplierId} not found.");
    }
}