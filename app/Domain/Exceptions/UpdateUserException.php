<?php

namespace App\Domain\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UpdateUserException extends Exception
{
   public function __construct($message = 'Error updeted user ', $code = Response::HTTP_UNPROCESSABLE_ENTITY, Exception $previous = null)
   {
        parent::__construct($message, $code, $previous);
   }
}