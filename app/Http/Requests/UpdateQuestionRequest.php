<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'nullable|exists:categories,id',
            'level_id' => 'nullable|exists:levels,id',
            'question_text' => 'required|string',
            'option_1' => 'nullable|string',
            'option_2' => 'nullable|string',
            'option_3' => 'nullable|string',
            'option_4' => 'nullable|string',
            'answer_text' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $options = [
                        trim($this->input('option_1')),
                        trim($this->input('option_2')),
                        trim($this->input('option_3')),
                        trim($this->input('option_4')),
                    ];
                    // Remove empty options
                    $options = array_filter($options, fn($val) => $val !== '');
                    
                    if (!empty($options) && !in_array(trim($value), $options)) {
                        $fail('The correct answer must exactly match one of the options.');
                    }
                }
            ],
            'acceptable_answers' => 'nullable|string',
        ];
    }
}
