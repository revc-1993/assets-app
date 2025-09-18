<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use App\Http\Requests\TransactionStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;

class TransactionController extends Controller
{
    use \App\Traits\ApiResponse;
    protected TransactionService $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    // Listar todas las transacciones
    public function index()
    {
        $transactions = Transaction::with([
            'transactionType:id,type_name',
            'department:id,department_name,location',
            'verification:id,names',
            'custodian:id,names',
            'responsibleGiza:id,names',
            'responsibleGafyb:id,names',
            'transactionDetails.asset:id,esbye_code,serie,description,model,condition'
        ])->get();

        if (!$transactions) {
            return $this->errorResponse(null, 'Error de transacciones', 404);
        }

        if ($transactions->isEmpty()) {
            return $this->successResponse($transactions, 'No hay transacciones disponibles');
        }

        return $this->successResponse($transactions, 'Transacciones encontradas');
    }

    // Mostrar una transacción específica
    public function show(int $id)
    {
        $transaction = Transaction::with([
            'transactionType:id,type_name',
            'department:id,department_name,location',
            'verification:id,names',
            'custodian:id,names',
            'responsibleGiza:id,names',
            'responsibleGafyb:id,names',
            'transactionDetails.asset:id,esbye_code,serie,description,model,condition,book_value,employee_id,department_id,inactive,registered_esbye,comments,origin'
        ])->find($id);

        if (!$transaction) {
            return $this->errorResponse(null, 'Transacción no encontrada', 404);
        }

        return $this->successResponse($transaction, 'Transacción encontrada');
    }

    // Crear una nueva transacción
    public function store(TransactionStoreRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user->id ?? null;
        $data['action'] = 'created';

        $items = $request['items'] ?? [];

        $transaction = $this->service->storeTransaction($data, $items);

        return $this->successResponse($transaction, 'Transacción creada exitosamente', 201);
    }

    // Actualizar una transacción existente
    public function update(TransactionUpdateRequest $request, int $id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return $this->errorResponse(null, 'Transacción no encontrada', 404);
        }

        $data = $request->validated();
        $data['action'] = 'updated';

        $items = $request['items'] ?? [];

        // Llama al servicio para manejar la lógica de actualización.
        $updatedTransaction = $this->service->updateTransaction($transaction, $data, $items);

        return $this->successResponse($updatedTransaction, 'Transacción actualizada exitosamente', 200);
    }
}
