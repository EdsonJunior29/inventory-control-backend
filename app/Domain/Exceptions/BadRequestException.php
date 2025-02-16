<?php

namespace App\Domain\Exceptions;

use Exception;
use Illuminate\Http\Response;

class BadRequestException extends Exception
{
   public function __construct($message = 'Bad Request ', $code = Response::HTTP_BAD_REQUEST, Exception $previous = null)
   {
        parent::__construct($message, $code, $previous);
   }
}