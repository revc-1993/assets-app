<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;

class TransactionController extends Controller
{
    // Listar todas las transacciones
    public function index()
    {
        return response()->json(Transaction::all());
    }

    // Mostrar una transacción específica
    public function show(int $id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['message' => 'Transacción no encontrada'], 404);
        }
        return response()->json($transaction);
    }

    // Crear una nueva transacción
    public function store(TransactionStoreRequest $request)
    {
        $transaction = $request->validated();

        // Obtener el siguiente número para el tipo de transacción
        $lastNumber = Transaction::where(
            'transaction_type_id',
            $transaction['transaction_type_id']
        )
            ->max('sequence_number');
        $transaction['sequence_number'] = $lastNumber ? $lastNumber + 1 : 1;

        $transaction['created_by'] = $request->user->id ?? null;
        $transaction['action'] = $transaction['action'] ?? 'created';

        $transaction = Transaction::create($transaction);
        return response()->json($transaction, 201);
    }

    // Actualizar una transacción existente
    public function update(TransactionUpdateRequest $request, int $id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['message' => 'Transacción no encontrada'], 404);
        }

        $transaction = $request->validated();
        $transaction['action'] = $validated['action'] ?? 'edited';

        $transaction->update($transaction);
        return response()->json($transaction);
    }
}
