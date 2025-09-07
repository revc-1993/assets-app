<?php

namespace App\Traits;

trait ApiResponse
{
    protected function successResponse($data = null, string $message = 'Operación exitosa', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'errors'  => null,
            'data'    => $data,
        ], $code);
    }

    protected function errorResponse($errors = null, string $message = 'Error en la operación', int $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
            'data'    => null,
        ], $code);
    }
}
