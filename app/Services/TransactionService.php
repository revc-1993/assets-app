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
                    'asset_id'       => $item['asset_id'],
                    'comments'       => $item['comments'] ?? '',
                ]);
                $transactionDetail->save();

                // Lógica según tipo de transacción
                $this->processAsset($transaction['transaction_type_id'], $item, $transaction);
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
            // Actualiza los datos principales de la transacción.
            $transaction->update($data);

            // Elimina todos los detalles existentes para re-crearlos.
            $transaction->transactionDetails()->delete();

            // Vuelve a crear los detalles de la transacción.
            foreach ($items as $item) {
                $transactionDetail = new TransactionDetail([
                    'transaction_id' => $transaction['id'],
                    'asset_id'       => $item['asset_id'],
                    'comments'       => $item['comments'] ?? '',
                ]);
                $transactionDetail->save();

                // Procesa el bien asociado según el tipo de transacción.
                $this->processAsset($transaction->transaction_type_id, $item, $transaction);
            }

            return $transaction->load('transactionDetails');
        });
    }

    /**
     * Procesa el bien (Asset) según el tipo de transacción.
     *
     * @param int $typeId El ID del tipo de transacción.
     * @param array $item Los datos del detalle de la transacción para el bien.
     * @param Transaction $transaction La transacción completa.
     * @return void
     */
    protected function processAsset($typeId, $item, $transaction)
    {
        // Puedes usar constantes o IDs fijos para los tipos
        // Ejemplo: 1=Ingreso, 2=Ajuste, 3=Encargo, 4=Descargo
        $asset = Asset::find($item['asset_id']);
        if (!$asset) {
            // Si el bien no existe y el tipo de transacción es Ingreso, lo creamos.
            if ($typeId == TransactionTypes::TYPE_INCOME) {
                Asset::create([
                    'esbye_code'  => $item['esbye_code'],
                    'description' => $item['description'] ?? '',
                    'serie' => $item['serie'] ?? '',
                    'model' => $item['model'] ?? '',
                    'condition' => $item['condition'] ?? '',
                    'book_value' => $item['book_value'] ?? 0,
                    'employee_id' => $item['employee_id'] ?? null,
                    'department_id' => $item['department_id'] ?? null,
                    'inactive'      => false, // Suponemos que al ingresar un bien, está activo.
                    'registered_esbye' => false,
                    'comments' => $item['comments'] ?? '',
                    'origin' => $item['origin'] ?? '',
                ]);
            }
            return;
        }

        switch ($typeId) {
            case TransactionTypes::TYPE_INCOME: // Ingreso
                // La lógica de creación ya está manejada arriba.
                break;
            case TransactionTypes::TYPE_ADJUSTMENT: // Ajuste
                $asset['inactive'] = true;
                $asset->save();
                break;
            case TransactionTypes::TYPE_ASSIGNMENT: // Encargo
                $asset['employee_id'] = $transaction['custodian_id'];
                $asset->save();
                break;
            case TransactionTypes::TYPE_DISCHARGE: // Descargo
                $asset['employee_id'] = null;
                $asset->save();
                break;
        }
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
