<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAttemptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'student';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'answers'     => ['required', 'array'],
            'answers.*.question_id' => ['required', 'integer', 'exists:questions,id'],
            'answers.*.selected'    => ['nullable', 'array'], // For MCQ
            'answers.*.selected.*'  => ['integer', 'exists:options,id'],
            'answers.*.text'        => ['nullable', 'string'], // For text answers
            'started_at'  => ['nullable', 'date']
        ];
    }
}
