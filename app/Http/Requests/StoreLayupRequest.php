<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLayupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supplierId = $this->route('supplier')?->id ?? $this->input('supplier_id');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clt_layups')->where('supplier_id', $supplierId),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
