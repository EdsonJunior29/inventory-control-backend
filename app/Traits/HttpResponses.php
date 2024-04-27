<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait HttpResponses 
{
    protected function success($data, $message = null, $code = Response::HTTP_OK)
    {
        return response()->json([
            'status' => 'Request was succesful.',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error($data, $message = null, $code)
    {
        return response()->json([
            'status' => 'Error has occurred',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}