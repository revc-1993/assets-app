<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use App\Enums\TransactionTypeConstants as TransactionTypes;

class TransactionService
{
    /**
     * @var AssetService
     */
    protected AssetService $assetService;

    /**
     * Inyecta el AssetService en el constructor.
     *
     * @param AssetService $assetService
     */
    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    /**
     * Almacena una nueva transacción y sus detalles, actualizando el estado de los bienes.
     *
     * @param array $data Datos de la transacción.
     * @param array $items Detalles de los bienes.
     * @return Transaction
     */
    public function storeTransaction(array $data, array $items)
    {
        return DB::transaction(function () use ($data, $items) {
            // Numeración independiente por tipo
            $data['sequence_number'] = $this->getNextSequenceNumber($data['transaction_type_id']);

            $transaction = Transaction::create($data);

            foreach ($items as $item) {
                $transactionDetail = new TransactionDetail([
                    'transaction_id' => $transaction['id'],
                    'asset_id'       => $item['asset_id'] ?? null,
                    'comments'       => $item['comments'] ?? '',
                ]);
                $transactionDetail->save();

                // Lógica para procesar el bien, ahora en el AssetService
                $this->assetService->processAsset($transaction['transaction_type_id'], $item, $transaction);
            }

            return $transaction->load('transactionDetails');
        });
    }

    /**
     * Actualiza una transacción existente.
     *
     * @param Transaction $transaction La instancia de la transacción a actualizar.
     * @param array $data Los datos validados para la transacción.
     * @param array $items Los ítems de la transacción para reemplazar.
     * @return Transaction
     */
    public function updateTransaction(Transaction $transaction, array $data, array $items)
    {
        return DB::transaction(function () use ($transaction, $data, $items) {
            // Elimina todos los detalles existentes para re-crearlos.
            $transaction->transactionDetails()->delete();

            // Vuelve a crear los detalles de la transacción.
            foreach ($items as $item) {
                $transactionDetail = new TransactionDetail([
                    'transaction_id' => $transaction['id'],
                    'asset_id'       => $item['asset_id'] ?? null,
                    'comments'       => $item['comments'] ?? '',
                ]);
                $transactionDetail->save();

                // Procesa el bien asociado según el tipo de transacción, ahora en el AssetService
                $this->assetService->processAsset($transaction['transaction_type_id'], $item, $transaction);
            }

            // Actualiza los datos principales de la transacción.
            $transaction->update($data);

            return $transaction->load('transactionDetails');
        });
    }

    // ******************** UTILS ********************

    /**
     * Obtiene el siguiente número de secuencia para un tipo de transacción.
     *
     * @param int $transactionTypeId
     * @return int
     */
    protected function getNextSequenceNumber(int $transactionTypeId)
    {
        $lastNumber = Transaction::where('transaction_type_id', $transactionTypeId)
            ->max('sequence_number');
        return $lastNumber ? $lastNumber + 1 : 1;
    }
}
