<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Transaction;
use App\Enums\TransactionTypeConstants as TransactionTypes;

final class AssetService
{
    /**
     * Procesa el bien (Asset) según el tipo de transacción.
     *
     * @param int $typeId El ID del tipo de transacción.
     * @param array $item Los datos del detalle de la transacción para el bien.
     * @param Transaction $transaction La transacción completa.
     * @return void
     */
    public function processAsset(int $typeId, array $item, Transaction $transaction)
    {
        $asset = Asset::find($item['asset_id']);

        if (!$asset) {
            // Si el bien no existe y el tipo de transacción es Ingreso, lo creamos.
            if ($typeId == TransactionTypes::TYPE_INCOME) {
                Asset::create([
                    'esbye_code' => $item['esbye_code'] ?? null,
                    'description' => $item['description'] ?? '',
                    'serie' => $item['serie'] ?? '',
                    'model' => $item['model'] ?? '',
                    'condition' => $item['condition'] ?? '',
                    'book_value' => $item['book_value'] ?? 0,
                    'employee_id' => $item['employee_id'] ?? null,
                    'department_id' => $item['department_id'] ?? null,
                    'inactive' => false,
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

    /**
     * Busca un bien por su código ESBYE o por su número de serie.
     *
     * @param string $searchTerm El término de búsqueda (código o serie).
     * @return Asset|null
     */
    public function findAssetByCodeOrSerie(string $searchTerm)
    {
        return Asset::where('esbye_code', $searchTerm)
            ->orWhere('serie', $searchTerm)
            ->first();
    }
}
