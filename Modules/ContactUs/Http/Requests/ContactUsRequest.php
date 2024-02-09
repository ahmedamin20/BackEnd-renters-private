<?php

namespace Modules\ContactUs\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
{
    use HttpResponse;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ValidationRuleHelper::stringRules(),
            'email' => ValidationRuleHelper::emailRules(['unique' => '']),
            'phone' => ValidationRuleHelper::phoneRules(),
            'message' => ValidationRuleHelper::longTextRules(),
        ];
    }

    public function messages(): array
    {
        $messages = [];

        foreach (array_keys($this->rules()) as $key) {
            $messages["$key.required"] = translate_error_message($key, 'required');
        }

        return $messages;
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
