<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponse;
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
        // 🔹 Para errores de validación (ya controlados en BaseFormRequest)
        if ($e instanceof ValidationException) {
            return $this->errorResponse(
                $e->errors(),
                'Errores de validación',
                422
            );
        }

        // 🔹 Cuando no encuentra un modelo
        if ($e instanceof ModelNotFoundException) {
            return $this->errorResponse(
                null,
                'Recurso no encontrado',
                404
            );
        }

        // 🔹 Cuando no está autenticado
        if ($e instanceof AuthenticationException) {
            return $this->errorResponse(
                null,
                'No autenticado',
                401
            );
        }

        // 🔹 Para cualquier otro error no manejado
        return $this->errorResponse(
            config('app.debug') ? $e->getMessage() : 'Error interno del servidor',
            'Error interno',
            500
        );
    }
}
