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

    protected function notFound($message = 'Resource not found.', $code = Response::HTTP_NOT_FOUND)
    {
        return response()->json([
            'message' => $message,
        ], $code);
    }

    protected function unauthorized($data, $message = 'Unauthorized.', $code = Response::HTTP_UNAUTHORIZED)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }


    protected function badRequest($message = 'Bad Request', $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'message' => $message,
        ], $code);
    }
}