<?php

namespace App\Api\Traits;

use Illuminate\Http\Response;

trait HttpResponses 
{
    protected function success($data, $message = 'Request was succesful.', $code = Response::HTTP_OK)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error($data, $message = null, $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json([
            'status' => 'Error has occurred',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}