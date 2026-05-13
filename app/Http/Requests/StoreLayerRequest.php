<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $layupId = $this->route('layup')?->id ?? $this->input('layup_id');

        return [
            'layer_order' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('clt_layers')->where('layup_id', $layupId),
            ],
            'thickness' => ['required', 'numeric', 'min:0'],
            'width'     => ['required', 'numeric', 'min:0'],
            'angle'     => ['required', 'numeric'],
            'species'   => ['nullable', 'string', 'max:100'],
            'grade'     => ['nullable', 'string', 'max:50'],
        ];
    }
}
