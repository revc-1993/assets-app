<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use App\Http\Requests\TransactionStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;

class TransactionController extends Controller
{
    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

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
        $data = $request->validated();

        $data['created_by'] = $request->user->id ?? null;
        $data['action'] = $data['action'] ?? 'created';

        $items = $request['items'] ?? [];

        $transaction = $this->service->storeTransaction($data, $items);

        return response()->json($transaction, 201);
    }

    // Actualizar una transacción existente
    public function update(TransactionUpdateRequest $request, int $id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['message' => 'Transacción no encontrada'], 404);
        }

        $data = $request->validated();
        $data['action'] = $data['action'] ?? 'updated';

        $items = $request['items'] ?? [];

        // Llama al servicio para manejar la lógica de actualización.
        $updatedTransaction = $this->service->updateTransaction($transaction, $data, $items);

        return response()->json($updatedTransaction, 200);
    }
}
