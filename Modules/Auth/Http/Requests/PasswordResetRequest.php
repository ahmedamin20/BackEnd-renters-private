<?php

namespace Modules\Auth\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use App\Rules\KeyExists;
use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
{
    use HttpResponse;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'handle' => new KeyExists(),
        ];

        if (preg_match('/.*reset_password$/', $this->url())) {
            $rules['code'] = ['required', 'numeric'];
            $rules['password'] = ValidationRuleHelper::defaultPasswordRules();
        }

        return $rules;
    }

    public function failedValidation(Validator $validator): void
    {
        $this->throwValidationException($validator);
    }
}
