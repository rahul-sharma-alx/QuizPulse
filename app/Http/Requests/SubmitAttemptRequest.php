<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Questions;
use App\Models\Options;

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
            'answers.*.text'        => ['nullable', 'string'],
            'started_at'  => ['nullable', 'date']
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $answers = $this->input('answers');
            if (!is_array($answers)) {
                return; // If answers is not an array, basic validation already failed.
            }

            foreach ($answers as $index => $answer) {
                $questionId = $answer['question_id'] ?? null;
                $selectedAnswer = $answer['selected'] ?? null;

                if ($questionId) {
                    $question = Questions::find($questionId);

                    if ($question) {
                        if ($question->type === 'single') {
                            // For single choice, 'selected' should be a single integer
                            if (isset($selectedAnswer) && !is_numeric($selectedAnswer)) {
                                $validator->errors()->add("answers.{$index}.selected", 'The selected answer for single choice question must be an integer.');
                            }
                            // Also validate that the selected option exists
                            if (is_numeric($selectedAnswer) && !\App\Models\Options::where('id', $selectedAnswer)->exists()) {
                                $validator->errors()->add("answers.{$index}.selected", 'The selected option is invalid.');
                            }
                        } elseif ($question->type === 'multiple') {
                            // For multiple choice, 'selected' should be an array of integers
                            if (isset($selectedAnswer) && !is_array($selectedAnswer)) {
                                $validator->errors()->add("answers.{$index}.selected", 'The selected answers for multiple choice question must be an array.');
                            } elseif (is_array($selectedAnswer)) {
                                foreach ($selectedAnswer as $optionId) {
                                    if (!is_numeric($optionId) || !\App\Models\Options::where('id', $optionId)->exists()) {
                                        $validator->errors()->add("answers.{$index}.selected", 'One or more selected options are invalid.');
                                        break; // Stop checking further options for this question
                                    }
                                }
                            }
                        }
                    }
                }
            }
        });
    }
}
