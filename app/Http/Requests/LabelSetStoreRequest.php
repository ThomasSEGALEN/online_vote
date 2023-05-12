<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabelSetStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'unique:label_sets,name'],
            'names' => ['required', 'array'],
            'names.*' => ['required', 'string'],
            'amount' => ['required', 'integer', 'min:1']
        ];
    }
}
