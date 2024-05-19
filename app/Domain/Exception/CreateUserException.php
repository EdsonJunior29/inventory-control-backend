<?php

namespace App\Domain\Exception;

use Exception;
use Illuminate\Http\Response;

class CreateUserException extends Exception
{
   public function __construct($message = 'Error creating user', $code = Response::HTTP_INTERNAL_SERVER_ERROR, Exception $previous = null)
   {
        parent::__construct($message, $code, $previous);
   }
}