<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionUpdateRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'users' => ['required', 'array'],
            'users.*' => ['integer'],
            'status' => ['required', 'integer'],
            'documents' => ['nullable', 'array', 'max_size:8192'],
            'documents.*' => ['file'],
            'votes' => ['required', 'array'],
            'votes.title.*' => ['required', 'string'],
            'votes.users.*' => ['required', 'array'],
            'votes.users.*.*' => ['integer'],
            'votes.status.*' => ['required', 'integer'],
            'votes.type.*' => ['required', 'integer'],
            'label_sets' => ['nullable', 'array'],
            'label_sets.*' => ['integer'],
            'votes.label_sets.*' => ['nullable', 'array'],
            'votes.label_sets.*.*' => ['integer']
        ];
    }
}
