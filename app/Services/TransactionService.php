<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use App\Enums\TransactionTypeConstants as TransactionTypes;

class TransactionService
{
    public function storeTransaction(array $data, array $items)
    {
        return DB::transaction(function () use ($data, $items) {
            // Numeración independiente por tipo
            $data['sequence_number'] = $this->getNextSequenceNumber($data['transaction_type_id']);

            $transaction = Transaction::create($data);

            foreach ($items as $item) {
                $detail = new TransactionDetail([
                    'transaction_id' => $transaction['id'],
                    'asset_id'       => $item['asset_id'],
                    'comments'       => $item['comments'] ?? '',
                ]);
                $detail->save();

                // Lógica según tipo de transacción
                $this->processAsset($transaction['transaction_type_id'], $item, $transaction);
            }

            return $transaction->load('transactionDetails');
        });
    }

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

    // ************ UTILS ************

    protected function getNextSequenceNumber(int $transactionTypeId)
    {
        $lastNumber = Transaction::where('transaction_type_id', $transactionTypeId)
            ->max('sequence_number');
        return $lastNumber ? $lastNumber + 1 : 1;
    }
}
