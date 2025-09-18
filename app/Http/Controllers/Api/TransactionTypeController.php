<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransactionType;
use Illuminate\Http\Request;

class TransactionTypeController extends Controller
{
    use \App\Traits\ApiResponse;

    /**
     * Listar todos los tipos de transacción
     */
    public function index()
    {
        $transactionTypes = TransactionType::all();

        if (!$transactionTypes) {
            return $this->errorResponse(null, 'Error de tipos de transacción', 404);
        }

        if ($transactionTypes->isEmpty()) {
            return $this->successResponse($transactionTypes, 'No hay tipos de transacción disponibles');
        }

        return $this->successResponse($transactionTypes, 'Lista de tipos de transacción obtenida correctamente');
    }

    /**
     * Mostrar un tipo de transacción específico
     */
    public function show(int $id)
    {
        $transactionType = TransactionType::find($id);

        if (!$transactionType) {
            return $this->errorResponse(null, 'Tipo de transacción no encontrado', 404);
        }

        return $this->successResponse($transactionType, 'Tipo de transacción encontrado');
    }

    /**
     * Crear un nuevo tipo de transacción
     */
    public function store(Request $request)
    {
        $transactionType = TransactionType::create($request->validated());

        return $this->successResponse($transactionType, 'Tipo de transacción creado correctamente', 201);
    }

    /**
     * Actualizar un tipo de transacción
     */
    public function update(Request $request, int $id)
    {
        $transactionType = TransactionType::find($id);

        if (!$transactionType) {
            return $this->errorResponse(null, 'Tipo de transacción no encontrado', 404);
        }

        $transactionType->update($request->validated());

        return $this->successResponse($transactionType, 'Tipo de transacción actualizado correctamente');
    }

    /**
     * Eliminar un tipo de transacción
     */
    public function destroy(int $id)
    {
        $transactionType = TransactionType::find($id);

        if (!$transactionType) {
            return $this->errorResponse(null, 'Departamento no encontrado', 404);
        }

        $transactionType->delete();

        return $this->successResponse(null, 'Departamento eliminado correctamente');
    }
}
