<?php

namespace App\Api\Traits;

use Illuminate\Http\Response;

trait HttpResponses 
{
    protected function success(
        $data,
        $message = 'Request was successful.', 
        $code = Response::HTTP_OK,
        $status = true
    ) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function successPaginated(
    $data,
    $message = 'Request was successful.',
    $code = Response::HTTP_OK,
    $status = true
    ) {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ] + $data, $code);
    }

    protected function create(
        $data,
        $message = 'successfully created',
        $code = Response::HTTP_CREATED,
        $status = true
    ) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }


    protected function updated(
        $data,
        $message = 'Resource updated successfully.',
        $code = Response::HTTP_OK,
        $status = true
    ) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error(
        $data,
        $message = 'Error has occurred',
        $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        $status = false
    ) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function notFound(
        $message = 'Resource not found.',
        $code = Response::HTTP_NOT_FOUND,
        $status = false
    ) {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $code);
    }

    protected function unauthorized(
        $data,
        $message = 'Unauthorized.', 
        $code = Response::HTTP_UNAUTHORIZED,
        $status = false
    ) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }


    protected function badRequest(
        $message = 'Bad Request',
        $code = Response::HTTP_BAD_REQUEST,
        $status = false
    ) {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $code);
    }
}