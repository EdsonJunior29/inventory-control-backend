<?php

namespace App\Domain\Exceptions;

use Exception;
use Illuminate\Http\Response;

class EmptyDataException extends Exception
{
   public function __construct($message = 'The data is empty.', $code = Response::HTTP_OK, Exception $previous = null)
   {
        parent::__construct($message, $code, $previous);
   }
}