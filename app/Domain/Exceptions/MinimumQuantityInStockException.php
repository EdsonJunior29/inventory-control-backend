<?php

namespace App\Domain\Exceptions;

use Exception;
use Illuminate\Http\Response;

class MinimumQuantityInStockException extends Exception
{
   public function __construct($message = 'Minimum acceptable stock quantity is 1', $code = Response::HTTP_UNPROCESSABLE_ENTITY, Exception $previous = null)
   {
        parent::__construct($message, $code, $previous);
   }
}