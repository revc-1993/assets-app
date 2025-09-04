<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\TransactionTypeConstants as TransactionTypes;

class TransactionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_type_id' => [
                'sometimes',
                'required',
                'exists:transaction_types,id',
                function ($attribute, $value, $fail) {
                    $this->validateTransactionType($attribute, $value, $fail);
                },
            ],
            'request'                => ['sometimes', 'required', 'string', 'max:255'],
            'transaction_date'       => ['sometimes', 'required', 'date'],
            'verification_id'        => ['nullable', 'exists:employees,id'],
            'custodian_id'           => ['nullable', 'exists:employees,id'],
            'responsible_giza_id'    => ['nullable', 'exists:employees,id'],
            'responsible_gafyb_id'   => ['nullable', 'exists:employees,id'],
            'delivery_id'            => ['nullable', 'exists:employees,id'],
            'receive_id'             => ['nullable', 'exists:employees,id'],
            'comments'               => ['nullable', 'string'],
            'department_id'          => ['sometimes', 'required', 'exists:departments,id'],
            'action'                 => ['in:created,edited'],
            'items' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $this->validateUniqueItems($attribute, $value, $fail);
                },
            ],
            'items.*.asset_id' => ['required', 'exists:assets,id'],
            'items.*.comments' => ['nullable', 'string'],
        ];
    }

    /**
     * Valida si el tipo de transacción puede ser actualizado y si se ha modificado.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure $fail
     */
    private function validateTransactionType($attribute, $value, $fail)
    {
        // Obtiene la instancia de la transacción que se está actualizando
        $transaction = $this->route('transaction');
        $currentTypeId = $transaction['transaction_type_id'];

        // Regla 1: No permitir la edición de transacciones de tipo 'Ingreso' o 'Ajuste'.
        if ($currentTypeId === TransactionTypes::TYPE_INCOME || $currentTypeId === TransactionTypes::TYPE_ADJUSTMENT) {
            $fail('No se puede editar una transacción de tipo Ingreso o Ajuste.');
        }

        // Regla 2: No permitir cambiar el tipo de transacción en una edición.
        if ($value !== $currentTypeId) {
            $fail('El tipo de transacción no puede ser modificado.');
        }
    }

    /**
     * Valida que no haya items repetidos en el arreglo.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure $fail
     */
    private function validateUniqueItems($attribute, $value, $fail)
    {
        $seenIds = [];
        foreach ($value as $item) {
            // Verifica que el campo 'asset_id' existe antes de intentar acceder a él.
            if (!isset($item['asset_id'])) {
                continue;
            }

            if (in_array($item['asset_id'], $seenIds)) {
                $fail("El bien con ID '{$item['asset_id']}' está duplicado en la lista.");
            }
            $seenIds[] = $item['asset_id'];
        }
    }
}
