<?php

namespace Modules\TermsAndConditions\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class TermConditionRequest extends FormRequest
{
    use HttpResponse;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'content' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'content.required' => translate_error_message('content', 'required'),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
