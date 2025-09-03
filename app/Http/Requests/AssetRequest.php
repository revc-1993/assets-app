<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
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
        $id = $this->route('asset');
        return [
            'esbye_code'      => [
                'required',
                'integer',
                'unique:assets,esbye_code' . ($id ? ',' . $id : '')
            ],
            'description'     => ['required', 'string', 'max:200'],
            'serie'           => ['nullable', 'string', 'max:80'],
            'model'           => ['nullable', 'string', 'max:70'],
            'condition'       => ['nullable', 'string', 'max:40'],
            'book_value'      => ['nullable', 'numeric'],
            'employee_id'     => ['nullable', 'exists:employees,id'],
            'department_id'   => ['required', 'exists:departments,id'],
            'inactive'        => ['boolean'],
            'registered_esbye' => ['boolean'],
            'comments'        => ['nullable', 'string'],
            'origin'          => ['nullable', 'string'],
        ];
    }
}
