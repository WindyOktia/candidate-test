<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $layer = $this->route('layer');
        $layupId = $layer?->layup_id;

        return [
            'layer_order' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('clt_layers')->where('layup_id', $layupId)->ignore($layer),
            ],
            'thickness' => ['required', 'numeric', 'min:0'],
            'width'     => ['required', 'numeric', 'min:0'],
            'angle'     => ['required', 'numeric'],
            'species'   => ['nullable', 'string', 'max:100'],
            'grade'     => ['nullable', 'string', 'max:50'],
        ];
    }
}
