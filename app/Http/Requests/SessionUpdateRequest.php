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
            'start_date' => ['nullable', 'date', 'before:end_date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'users' => ['required', 'array'],
            'users.*' => ['integer'],
            'status' => ['required', 'integer'],
            'documents' => ['nullable', 'array', 'max_size:10000'],
            'documents.*' => ['file'],
            'votes' => ['required', 'array'],
            'votes.title.*' => ['required', 'string'],
            'votes.description' => ['nullable', 'string'],
            'votes.start_date' => ['nullable', 'date', 'before:end_date'],
            'votes.end_date' => ['nullable', 'date', 'after:start_date'],
            'votes.users.*' => ['required', 'array'],
            'votes.users.*.*' => ['integer'],
            'votes.status.*' => ['required', 'integer'],
            'votes.type.*' => ['required', 'integer'],
            'label_sets' => ['nullable', 'array'],
            'label_sets.*' => ['integer'],
            'votes.label_sets.*' => ['nullable', 'array'],
            'votes.label_sets.*.*' => ['integer'],
            'votes.answers.*' => ['nullable', 'array'],
            'votes.answers.*.*' => ['array'],
            'votes.answers.*.*.name' => ['required_without:votes.label_sets.*.*']
        ];
    }
}
