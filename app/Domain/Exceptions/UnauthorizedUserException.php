<?php

namespace App\Domain\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UnauthorizedUserException extends Exception
{
   public function __construct($message = 'Credentials do not match', $code = Response::HTTP_UNAUTHORIZED, Exception $previous = null)
   {
        parent::__construct($message, $code, $previous);
   }
}