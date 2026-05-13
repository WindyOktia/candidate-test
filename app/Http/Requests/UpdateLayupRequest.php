<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLayupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $layup = $this->route('layup');
        $supplierId = $layup?->supplier_id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clt_layups')->where('supplier_id', $supplierId)->ignore($layup),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
