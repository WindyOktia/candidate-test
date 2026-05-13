<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file'     => ['required', 'file', 'mimes:xlsx,xls', 'max:8192'],
            'strategy' => ['required', 'in:overwrite,skip,duplicate,reject,resolve'],
            'dry_run'  => ['sometimes', 'boolean'],
        ];
    }
}
