<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class InternalServerErrorException extends Exception
{
   public function __construct($message = 'Internal Server Error ', $code = Response::HTTP_INTERNAL_SERVER_ERROR, Exception $previous = null)
   {
        parent::__construct($message, $code, $previous);
   }
}