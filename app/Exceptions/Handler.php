<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponse;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    public function render($request, Throwable $e)
    {
        // Validación
        if ($e instanceof ValidationException) {
            return $this->errorResponse($e->errors(), 'Errores de validación', 422);
        }

        // Modelo no encontrado
        if ($e instanceof ModelNotFoundException) {
            return $this->errorResponse(null, 'Recurso no encontrado', 404);
        }

        // No autenticado
        if ($e instanceof AuthenticationException) {
            return $this->errorResponse(null, 'No autenticado', 401);
        }

        // Errores de BD
        if ($e instanceof QueryException) {
            return $this->errorResponse(
                config('app.debug') ? $e->getMessage() : 'Error en la base de datos',
                'Error en la base de datos',
                500
            );
        }

        // Cualquier otro error
        return $this->errorResponse(
            config('app.debug') ? $e->getMessage() : 'Error interno del servidor',
            'Error interno',
            500
        );
    }
}
