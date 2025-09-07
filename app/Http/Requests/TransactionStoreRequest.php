<?php

namespace App\Http\Requests;

use App\Models\Asset;

class TransactionStoreRequest extends BaseFormRequest
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
            'transaction_type_id'    => ['required', 'exists:transaction_types,id'],
            'request'                => ['required', 'string', 'max:255'],
            'transaction_date'       => ['required', 'date'],
            'verification_id'        => ['nullable', 'exists:employees,id'],
            'custodian_id'           => ['nullable', 'exists:employees,id'],
            'responsible_giza_id'    => ['nullable', 'exists:employees,id'],
            'responsible_gafyb_id'   => ['nullable', 'exists:employees,id'],
            'delivery_id'            => ['nullable', 'exists:employees,id'],
            'receive_id'             => ['nullable', 'exists:employees,id'],
            'comments'               => ['nullable', 'string'],
            'department_id'          => ['required', 'exists:departments,id'],
            'action'                 => ['in:created,edited'],
            'items' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $this->validateAssetUniqueness($value, $fail);
                },
            ],
            'items.*.asset_id' => ['required', 'exists:assets,id'],
            'items.*.comments'   => ['nullable', 'string'],
        ];
    }

    /**
     * Valida la unicidad de los bienes en la base de datos y en la misma petición.
     *
     * @param array $items
     * @param \Closure $fail
     */
    private function validateAssetUniqueness(array $items, \Closure $fail)
    {
        $seenCombinations = [];

        foreach ($items as $index => $item) {
            $esbyeCode = $item['esbye_code'] ?? null;
            $serie = $item['serie'] ?? null;

            if ($esbyeCode) {
                // Validar unicidad dentro del mismo request
                if (in_array($esbyeCode, $seenCombinations)) {
                    $fail("El código ESBYE '{$esbyeCode}' está duplicado en la lista de ítems.");
                }
                $seenCombinations[] = $esbyeCode;

                // Validar en la base de datos
                if (Asset::where('esbye_code', $esbyeCode)->exists()) {
                    $fail("El bien con el código ESBYE '{$esbyeCode}' ya está registrado.");
                }
            } elseif ($serie) {
                // Validar unicidad dentro del mismo request
                if (in_array($serie, $seenCombinations)) {
                    $fail("La serie '{$serie}' está duplicada en la lista de ítems.");
                }
                $seenCombinations[] = $serie;

                // Validar en la base de datos
                if (Asset::where('serie', $serie)->exists()) {
                    $fail("El bien con la serie '{$serie}' ya está registrado.");
                }
            }
        }
    }
}
