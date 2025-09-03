<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
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
        ];
    }
}
