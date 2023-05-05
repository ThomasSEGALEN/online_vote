<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionPrestoreRequest extends FormRequest
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
            'title' => ['required', 'string', 'unique:sessions'],
            'users' => ['required', 'array'],
            'users.*' => ['integer'],
            'status' => ['required', 'integer'],
            'documents' => ['nullable', 'array', 'max_size:8192'],
            'documents.*' => ['file'],
            'amount' => ['required', 'integer']
        ];
    }
}
