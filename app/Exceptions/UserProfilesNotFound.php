<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UserProfilesNotFound extends Exception
{
   public function __construct($message = 'User profiles not found', $code = Response::HTTP_NOT_FOUND, Exception $previous = null)
   {
        parent::__construct($message, $code, $previous);
   }
}