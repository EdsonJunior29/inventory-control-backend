<?php

namespace App\Domain\Exceptions;

use Exception;
use Illuminate\Http\Response;

class QueryExecutionException extends Exception
{
   public function __construct($message = 'Error when performing transactions with the database.', $code = Response::HTTP_INTERNAL_SERVER_ERROR, Exception $previous = null)
   {
        parent::__construct($message, $code, $previous);
   }
}